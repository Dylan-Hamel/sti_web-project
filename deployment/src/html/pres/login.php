<? 
function SimpleCounter($file = "/usr/share/nginx/html/pres/SimpleCounter.txt") {
        if (!is_file($file)) {
                file_put_contents($file, 0);
                return 0;
        }
        $value = file_get_contents($file);
        $value ++;
        file_put_contents($file, $value, LOCK_EX); // LOCK the file
        return $value;
}
$i=SimpleCounter();
session_id("mysessionId".$i);


session_start();
header('Location: continue.php');
?>

<h1>login</h1>
