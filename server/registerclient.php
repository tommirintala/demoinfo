<?php



$time = time();
/**
 * @param string $msg
 * @param array<string> $args
 */
function log_sql(string $msg, array $args = []): void {
    $line = sprintf("DEBUG: $msg [%s]", join(', ', $args));
    syslog(LOG_DEBUG, $line);
}

/**
 * No composer, so we need to write our own config routine
 * @return array<string, string>
 */
function initialize(): array
{
    $config = [];
    if (file_exists(".env")) {
        $config = parse_ini_file(".env");
    }
    return $config;
}

/**
 * @param ?string $str
 */
function filter(?string $str): string
{
    if ($str === null) return "";
    $str = preg_replace("/[^a-zA-Z0-9:,.\/-]/", "", $str);
    return $str;
}

/**
 * @return array<string, string>
 */
function readargs(): array
{
    $result = [];
    $args = ['application', 'clientid', 'version', 'ip', 'time', 'uptime'];
    if (count($_REQUEST)) {
        foreach ($args as $arg) {
            if (isset($_REQUEST[$arg])) {
                $result[$arg] = filter($_REQUEST[$arg]);
            } else {
                $result[$arg] = '';
            }
        }
    }
    return $result;
}

/**
 * @param array<string, string> $x
 * @param array<string, string> $c
 */
function register_client(array $x, array $c): void
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli($x['DB_HOST'], $x['DB_USER'], $x['DB_PASS'], $x['DB_NAME']);
    $sql = "SELECT * FROM registery WHERE clientid='" . $c['clientid'] . "'";
    log_sql(__METHOD__, [$sql]);
    $result = $mysqli->execute_query($sql);
    if ($result->num_rows> 0) {
        $row = $result->fetch_assoc();
        $id = $row['id]'];
        $sql = sprintf(
            "UPDATE INTO registery SET updated_at=NOW(), application='%s', 'clientid='%s', version='%s', ip='%s', time=%d, uptime=%d WHERE id=%d",
            $c['application'],
            $c['clientid'],
            $c['version'],
            $c['ip'],
            $c['time'],
            $c['uptime'],
            $id
        );
    } else {
        $sql = sprintf(
            "INSERT INTO registery (application, clientid, version, ip, time, uptime) VALUES ('%s', '%s', '%s', '%s', %d, %d)",
            $c['application'],
            $c['clientid'],
            $c['version'],
            $c['ip'],
            $c['time'],
            $c['uptime']
        );
    }
    log_sql(__METHOD__, [$sql]);
    $mysqli->execute_query($sql);
}

/**
 * @param array<string,string> $c
 */
function show_clients(array $c): void
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli($c['DB_HOST'], $c['DB_USER'], $c['DB_PASS'], $c['DB_NAME']);

    $query = 'SELECT * FROM registery ORDER BY updated_at DESC';
    log_sql(__METHOD__, [$query]);
    $result = $mysqli->execute_query($query);
?>
    <table>
        <thead>
            <tr>
                <th>Application</th>
                <th>Client ID</th>
                <th>Version</th>
                <th>IP Address</th>
                <th>Device time</th>
                <th>Uptime</th>
                <th>Last update</th>
            </tr>
        </thead>
        <?php
        //foreach ($result as $row) {
        while ($row = $result->fetch_assoc()) {
            print("<tr>");
            printf("<td>%s</td>", $row['application']);
            printf("<td>%s</td>", $row['clientid']);
            printf("<td>%s</td>", $row['version']);
            printf("<td>%s</td>", $row['ip']);
            printf("<td>%s</td>", $row['time']);
            printf("<td>%s</td>", $row['uptime']);
            printf("<td>%s</td>", $row['updated_at']);
            print("</tr>");
        }
        ?>
    </table>
<?php
}

$config = initialize();
$args = readargs();

if (count($args)) {
    register_client($config, $args);
} else {
    show_clients($config);
}

//print "{\"status\":\"OK\",\"time\":\"$time\"}";
/*
            'application' => $appname,
            'clientid' => $clientid,
            'version' => $version,
            'ip' => join(', ', $ip),
            'time' => time(),
            'uptime' => $uptime,

*/
