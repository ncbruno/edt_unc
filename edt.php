<?php
include_once 'dom.php';  
$debug = true;  
header("Content-Type:text/plain;charset=utf-8");

function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}
 
function curlget($url, $body) {   
	$ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if ($body === 0) {
		curl_setopt($ch, CURLOPT_NOBODY, true);
	}
	$result = curl_exec($ch);
    //$errno = curl_errno($ch);
    //$error = curl_error($ch);
	//$info = curl_getinfo($ch); var_dump($info);
	curl_close($ch);
    //$final = array($result, $errno, $error);
	return($result);
}

$url = "http://applis.univ-nc.nc";
$http = $url."/cgi-bin/WebObjects/EdtWeb.woa";
$req  = curlget($http, 1);
$html = str_get_html($req);
$edt = $html->find('ul#mainMenu li a[title=EDT des formations]',0)->href;
echo $edt."\r\n";

$http = $url.$edt;
$req  = curlget($http, 1);
$html = str_get_html($req);
$edt = $html->find('a.menuItem[title=LICENCE]',0)->href;
echo $edt."\r\n";

$http = $url.$edt;
$req  = curlget($http, 1);
$html = str_get_html($req);
foreach($html->find('ul li a.menuItem') as $e) {
    if (preg_match('/SPI/',$e->innertext)) {
        $edt = $e->href;
    }
} 
echo $edt."\r\n";

$http = $url.$edt;
$req  = curlget($http, 1);
$html = str_get_html($req);
foreach($html->find('ul li ul li') as $e) {
    if (preg_match('/Parcours INFO/',$e->innertext)) {
        $html2 = str_get_html($e->innertext);
        foreach($html2->find('a') as $f) {
            if (preg_match('/6/',$f->innertext)) {
                $edt = $e->href;
            }
        }
    }
} 
echo $edt."\r\n";

$http = $url.$edt;
$req  = curlget($http, 1);
$html = str_get_html($req);
foreach($html->find('table tr td a') as $e) {
    $html2 = str_get_html($e->innertext);
    foreach($html2->find('font') as $f) {
        if (preg_match('/21/',$f->innertext)) {
            $edt = $e->href;
        }
    }
} 
echo $edt."\r\n";

$http = $url.$edt;
$req  = curlget($http, 1);
$html = str_get_html($req);
$pdf = $html->find('iframe[name=null]',0)->src;
echo $pdf."\r\n";
foreach($html->find('table tr td a') as $e) {
    $html2 = str_get_html($e->innertext);
    foreach($html2->find('font') as $f) {
        if (preg_match('/22/',$f->innertext)) {
            $edt = $e->href;
        }
    }
} 
echo $edt."\r\n";
