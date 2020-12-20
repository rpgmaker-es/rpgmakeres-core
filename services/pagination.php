<?php defined('RPGMAKERES') OR exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class PaginationService
 * Service used for allow paginate long lists of elements for a easier visualization
 */
class PaginationService {

    /**
     * Generates a user defined query with pagination, search-by key and order-by key available.
     * @param String $query User defined query
     * @param array $types An array defining data types of each dynamic value of the query. See https://www.php.net/manual/es/pdo.constants.php for possible data types
     * @param array $values An array (by reference) containing each value per dynamic value.
     * @param int $limit Size of the pagination (how many items a page will have). Default 10.
     * @param int $page Wich page you must load. Defautls to 0 (first page)
     * @param array $searchKey Key-value array with parameters to be searched (column=>value). IT MUST BE ALREADY VALIDATED.
     * @param array $orderKey Keys to make a order by. IT MUST BE ALREADY VALIDATED.
     * @return array|int The results on the query, or -1 in case of an error.
     */
    public static function buildPaginationSQL($query, $types = [], $values = [], $limit = 10, $page = 0, $searchKey = [], $orderKey = []) {

        $resultQuery = $query . " "; //making more space for next strings in the query

        //there's a where in the query already? If not, I must add a parameter in the query before!
        if (strpos(strtolower($resultQuery), 'where') === false) {
            $resultQuery .= "WHERE 1==1 ";
        }

        if ($page < 0 ) $page = 0;

        //I will add search keys to the query (and it's parameters to a separate array for build a secure query)
        //I will make the asumption search keys are already validated, so watch out!
        $params = $values;
        $params_types = $types;
        if (!$params) $params = [];
        if (!$params_types) $params_types = [];

        foreach($searchKey as $key => $value) {
            $resultQuery .= "AND ". $key . " LIKE ? ";
            array_push($params, "%" . $value . "%" );
            array_push($params_types, PDO::PARAM_STR);
        }

        //adding the order by
        if (count($orderKey) > 0 ) {
            $resultQuery .= " ORDER BY ";
            foreach ($orderKey as $orderItem) {
                $resultQuery.= $orderItem . ", ";
            }

            //removing last comma
            $resultQuery = substr($resultQuery, 0, -2);

        }

        //doing a full query before and count the results
        $mark1 = strpos(strtolower($resultQuery), "select");
        $mark2 = strpos(strtolower($resultQuery), "from");
        $query_content = substr($resultQuery, $mark1 + 6, $mark2 - ($mark1 + 6));
        $query_content = "COUNT(" . $query_content . ") as count";
        $intermediate_query = "select " . $query_content . " " . substr($resultQuery, $mark2);
        $count_out = PDOService::getSecureQuery($intermediate_query, $params_types, $params);

        //adding the last parameters
        $resultQuery.= " LIMIT ? OFFSET ?";
        array_push($params, $limit, $page * $limit);
        array_push($params_types, PDO::PARAM_INT, PDO::PARAM_INT);


        //I think we're ready for process the secure query:
        $out = [
            "result" => PDOService::getSecureQuery($resultQuery, $params_types, $params),
            "count" => (is_array($count_out) && count($count_out) > 0 )?$count_out[0]["count"]:0
        ];

        return $out;

    }

    /**
     * Build a structure of pagination parameters by GET values (confirmed by any parameter starting by search_xxx, order_xxx and page).
     * @return array[] A key-value array contiaing pagination parameters.
     */
    public static function getPaginationGetParams() {

        $out = [
            "search" => [],
            "order" => []
        ];

        //I think we can get the easy ones, huh?
        if (array_key_exists("page", $_GET) && ValidationService::isValidNumber($_GET["page"])) $out["page"] = $_GET["page"];
        else $out["page"] = 0;

        //seek all with search_ and order_ . No validation
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 7) == "search_") {
                $out["search"][substr($key, 7)] = $value;
            } else if (substr($key, 0, 6) == "order_") {
                //FIXME There's a strange case between GET parameters and spaces in the key; these are paresed as underscores (_) isntead spaces. This can affect the "string DESC" values... Meanwhile, there's a workaroud for this.
                array_push($out["order"], str_replace("_desc", " desc", substr($key, 6)));
            }
        }

        return $out;
    }

    /**
     * Change values in search inputted by user, trying to map into db proper values
     * @param $paginationParameters array Element that stores pagination parameters data
     * @param $searchKey string key in search to be evaluated
     * @param $valuesToMap array an key value array with the possible values to be mapped
     * @return mixed A new pagination parameters element that stores pagination parameters data
     */
    public static function mapSearchValues($paginationParameters, $searchKey, $valuesToMap) {
        if (array_key_exists($searchKey, $paginationParameters["search"])) {
            foreach($valuesToMap as $key => $value) {
                $paginationParameters["search"][$searchKey] = str_replace($key, $value, strtolower($paginationParameters["search"][$searchKey]));
            }
        }
        return $paginationParameters;
    }

    /**
     * Builds a URL with the current search/page/order settings
     * @param $paginationParameters array Element that stores pagination parameters data
     * @return string A URL
     */
    public static function viewBuildPaginationParameters($paginationParameters) {

        $out = [ "page" =>  $paginationParameters["page"]];

        foreach ($paginationParameters["search"] as $key => $value) {
            $out["search_" . $key] = $value;
        }

        foreach ($paginationParameters["order"] as $key) {
            $out["order_" . $key] = 1;
        }

        //obtain the url without get parameters (I will regenerate them)
        return $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST']
            . explode('?', $_SERVER['REQUEST_URI'], 2)[0] . "?" .http_build_query($out);

    }

    /**
     * Generate the header of a search table to provide the search/order tools.
     * @param $paginationParameters array Element that stores pagination parameters data
     * @param $values array a Key value array, where the key is the localized nice value, and the value is the column name in the db (or false if it must not be evaluated, or true if in that space one needs to provide extra control buttons).
     * @return string Dummy beacue the output is echoed.
     */
    public static function viewCreateTableHeaders($paginationParameters, $values) {

        $working_params = $paginationParameters;

        echo "<form><tr>\n";

        //going for order values
        foreach($values as $key => $value) {

            //if in pagination parameters the key doesn't exists in order array, offer enabling it
            //if in pagination parameters the key already exists in order array, offer a desc
            //if in pagination parameters the key already exists in desc, offer a disable

            $orderString = "";
            if ($value !== false && $value !== true) {
                if (in_array($value, $paginationParameters["order"])) {
                    //exists, offer in desc
                    $orderString = "⬆️";
                    $working_params["order"] = array_diff( $working_params["order"], [$value]);
                    array_push($working_params["order"], $value . " desc");
                    $url = PaginationService::viewBuildPaginationParameters($working_params);
                    array_pop($working_params["order"]);
                    array_push($working_params["order"], $value);

                } else if (in_array($value . " desc", $paginationParameters["order"])) {
                    //exists in desc, offer disabling it
                    $orderString = "⬇️";
                    $working_params["order"] = array_diff( $working_params["order"], [$value . " desc"]);
                    $url = PaginationService::viewBuildPaginationParameters($working_params);
                    array_push($working_params["order"], $value . " desc");

                } else {
                    //does not exist, offer enabling it
                    $orderString = "";
                    array_push($working_params["order"], $value);
                    $url = PaginationService::viewBuildPaginationParameters($working_params);
                    array_pop($working_params["order"]);
                }

                echo "<th><a href='" . $url . "'>" . $key . $orderString ."</a></th>\n";
            } else {
                echo "<th>" . $key . "</th>\n";
            }

        }
        echo "</tr><tr>";

        //going for the search values
        foreach($values as $key => $value) {
            echo "<th>";
            if ($value === true) {
                echo "<input type=\"submit\" value=\"Buscar\">";
                echo "<a href='" . $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST']
                    . explode('?', $_SERVER['REQUEST_URI'], 2)[0] ."'> Reiniciar</a>";
            } else if ($value !== false) {
                $filled_search_value = "";
                if (array_key_exists($value, $paginationParameters["search"])) {
                    $filled_search_value = "value=\"". $paginationParameters["search"][$value] ."\"";
                }
                echo "<input type=\"text\" name='search_". $value . "' placeholder=\"" . $key . "\" ". $filled_search_value . ">";

            }

            echo "</th>";
        }

        //Put the order values as hidden ones
        foreach($paginationParameters["order"] as $key) {
            echo "<input type=\"hidden\" name=\"order_" . $key . "\" value='1'>";
        }

        //end
        echo "</tr></form>\n";
        return "";
    }

    /**
     * Generate the page navigation.
     * @param $paginationParameters array Element that stores pagination parameters data
     * @return string The HTML output for page navigation
     */
    public static function viewCreatePageSeeker($paginationParameters) {
        global $_RPGMAKERES;
        //I'm on page $paginationParameters["page"] and there's $paginationParameters["count"] on pages of $_RPGMAKERES["config"]["paginationNumberOfItems"]

        $out = "\nPáginas: ";

        $working_params = $paginationParameters;
        $totalPages = intval($paginationParameters["count"] / $_RPGMAKERES["config"]["paginationNumberOfItems"]) + 1;

        for ($i=0; $i< $totalPages; $i++) {

            $working_params["page"] = $i;
            if ($i == $paginationParameters["page"]) {
                $page_string =  "<b>" . ($i + 1) . "</b>";
            } else {
                $page_string =  ($i + 1);
            }
            $out .= "<a href='" . PaginationService::viewBuildPaginationParameters($working_params) . "'>" . $page_string . "</b> ";
        }
        $out .= "\n";
        return $out;
    }

}