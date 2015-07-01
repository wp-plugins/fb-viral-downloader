<?php
class FB_Viral_Downloader_Get_Browser{
  private $agent = "";
  private $info = array();

  public function __construct(){
    $this->agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;
    $this->getBrowser();
    $this->getOS();
  }

  public function getBrowser(){
    $browser = array(
      "Navigator"            => "/Navigator(.*)/i",
      "Firefox"              => "/Firefox(.*)/i",
      "Internet Explorer"    => "/MSIE(.*)/i",
      "Chrome"               => "/chrome(.*)/i",
      "MAXTHON"              => "/MAXTHON(.*)/i",
      "Opera"                => "/Opera(.*)/i",
      "Safari"               => "/Safari(.*)/i",
      "Bolt"                 => "/Bolt(.*)/i",
      "Jasmine"              => "/Jasmine(.*)/i",
      "IceCat"               => "/IceCat(.*)/i",
      "Skyfire"              => "/Skyfire(.*)/i",
      "Midori"               => "/Midori(.*)/i",
      "Lynx"                 => "/Lynx(.*)/i",
      "Arora"                => "/Arora(.*)/i",
      "IBrowse"              => "/IBrowse(.*)/i",
      "Dillo"                => "/Dillo(.*)/i",
      "Camino"               => "/Camino(.*)/i",
      "Shiira"               => "/Shiira(.*)/i",
      "Fennec"               => "/Fennec(.*)/i",
      "Phoenix"              => "/Phoenix(.*)/i",
      "Flock"                => "/Flock(.*)/i",
      "Netscape"             => "/Netscape(.*)/i",
      "Lunascape"            => "/Lunascape(.*)/i",
      "Epiphany"             => "/Epiphany(.*)/i",
      "WebPilot"             => "/WebPilot(.*)/i",
      "Opera Mini"           => "/Opera Mini(.*)/i",
      "Vodafone"             => "/Vodafone(.*)/i",
      "NetFront"             => "/NetFront(.*)/i",
      "Netfront"             => "/Netfront(.*)/i",
      "Konqueror"            => "/Konqueror(.*)/i",
      "Googlebot"            => "/Googlebot(.*)/i",
      "SeaMonkey"            => "/SeaMonkey(.*)/i",
      "Kazehakase"           => "/Kazehakase(.*)/i",
      "Vienna"               => "/Vienna(.*)/i",
      "Iceape"               => "/Iceape(.*)/i",
      "Iceweasel"            => "/Iceweasel(.*)/i",
      "IceWeasel"            => "/IceWeasel(.*)/i",
      "Iron"                 => "/Iron(.*)/i",
      "K-Meleon"             => "/K-Meleon(.*)/i",
      "Sleipnir"             => "/Sleipnir(.*)/i",
      "Galeon"               => "/Galeon(.*)/i",
      "GranParadiso"         => "/GranParadiso(.*)/i",
      "iCab"                 => "/iCab(.*)/i",
      "NetNewsWire"          => "/NetNewsWire(.*)/i",
      "Space Bison"          => "/Space Bison(.*)/i",
      "Stainless"            => "/Stainless(.*)/i",
      "Orca"                 => "/Orca(.*)/i",
      "Dolfin"               => "/Dolfin(.*)/i",
      "BOLT"                 => "/BOLT(.*)/i",
      "Minimo"               => "/Minimo(.*)/i",
      "Tizen Browser"        => "/Tizen Browser(.*)/i",
      "Polaris"              => "/Polaris(.*)/i",
      "Abrowser"             => "/Abrowser(.*)/i",
      "Planetweb"            => "/Planetweb(.*)/i",
      "ICE Browser"          => "/ICE Browser(.*)/i",
    );
    foreach($browser as $key => $value) {
      if(preg_match($value, $this->agent)) {
        $this->info = array_merge($this->info,array("Browser" => $key));
        $this->info = array_merge($this->info,array("Version" => $this->getVersion($key, $value, $this->agent)));
        break;
      } else {
        $this->info = array_merge($this->info,array("Browser" => "UnKnown"));
        $this->info = array_merge($this->info,array("Version" => "UnKnown"));
      }
    }
    return $this->info['Browser'];
  }

  public function getOS() {
    $OS = array(
      "Windows"   =>   "/Windows/i",
      "Linux"     =>   "/Linux/i",
      "Unix"      =>   "/Unix/i",
      "Mac"       =>   "/Mac/i"
    );
    foreach($OS as $key => $value) {
      if(preg_match($value, $this->agent)) {
        $this->info = array_merge($this->info,array("Operating System" => $key));
        break;
      }
    }
    return $this->info['Operating System'];
  }

  public function getVersion($browser, $search, $string) {
    $browser = $this->info['Browser'];
    $version = "";
    $browser = strtolower($browser);
    preg_match_all($search,$string,$match);
    switch($browser){
      case "firefox": $version = str_replace("/","",$match[1][0]);
      break;

      case "internet explorer": $version = substr($match[1][0],0,4);
      break;

      case "opera": $version = str_replace("/","",substr($match[1][0],0,5));
      break;

      case "navigator": $version = substr($match[1][0],1,7);
      break;

      case "maxthon": $version = str_replace(")","",$match[1][0]);
      break;

      case "chrome": $version = substr($match[1][0],1,10);
      break;

      case "Safari": $version = substr($match[1][0],1,10);
      break;

      case "Bolt": $version = substr($match[1][0],1,10);
      break;

      case "Jasmine": $version = substr($match[1][0],1,10);
      break;

      case "IceCat": $version = substr($match[1][0],1,10);
      break;

      case "Skyfire": $version = substr($match[1][0],1,10);
      break;

      case "Midori": $version = substr($match[1][0],1,10);
      break;

      case "Lynx": $version = substr($match[1][0],1,10);
      break;

      case "Arora": $version = substr($match[1][0],1,10);
      break;

      case "IBrowse": $version = substr($match[1][0],1,10);
      break;

      case "Dillo": $version = substr($match[1][0],1,10);
      break;

      case "Camino": $version = substr($match[1][0],1,10);
      break;

      case "Shiira": $version = substr($match[1][0],1,10);
      break;

      case "Fennec": $version = substr($match[1][0],1,10);
      break;

      case "Phoenix": $version = substr($match[1][0],1,10);
      break;

      case "Flock": $version = substr($match[1][0],1,10);
      break;

      case "Netscape": $version = substr($match[1][0],1,10);
      break;

      case "Lunascape": $version = substr($match[1][0],1,10);
      break;

      case "Epiphany": $version = substr($match[1][0],1,10);
      break;

      case "WebPilot": $version = substr($match[1][0],1,10);
      break;

      case "Opera Mini": $version = substr($match[1][0],1,10);
      break;

      case "Vodafone": $version = substr($match[1][0],1,10);
      break;

      case "NetFront": $version = substr($match[1][0],1,10);
      break;

      case "Netfront": $version = substr($match[1][0],1,10);
      break;

      case "Konqueror": $version = substr($match[1][0],1,10);
      break;

      case "Googlebot": $version = substr($match[1][0],1,10);
      break;

      case "SeaMonkey": $version = substr($match[1][0],1,10);
      break;

      case "Kazehakase": $version = substr($match[1][0],1,10);
      break;

      case "Vienna": $version = substr($match[1][0],1,10);
      break;

      case "Iceape": $version = substr($match[1][0],1,10);
      break;

      case "Iceweasel": $version = substr($match[1][0],1,10);
      break;

      case "IceWeasel": $version = substr($match[1][0],1,10);
      break;

      case "Iron": $version = substr($match[1][0],1,10);
      break;

      case "K-Meleon": $version = substr($match[1][0],1,10);
      break;

      case "Sleipnir": $version = substr($match[1][0],1,10);
      break;

      case "Galeon": $version = substr($match[1][0],1,10);
      break;

      case "GranParadiso": $version = substr($match[1][0],1,10);
      break;

      case "iCab": $version = substr($match[1][0],1,10);
      break;

      case "NetNewsWire": $version = substr($match[1][0],1,10);
      break;

      case "Space Bison": $version = substr($match[1][0],1,10);
      break;

      case "Stainless": $version = substr($match[1][0],1,10);
      break;

      case "Orca": $version = substr($match[1][0],1,10);
      break;

      case "Dolfin": $version = substr($match[1][0],1,10);
      break;

      case "BOLT": $version = substr($match[1][0],1,10);
      break;

      case "Minimo": $version = substr($match[1][0],1,10);
      break;

      case "Tizen Browser": $version = substr($match[1][0],1,10);
      break;

      case "Polaris": $version = substr($match[1][0],1,10);
      break;

      case "Abrowser": $version = substr($match[1][0],1,10);
      break;

      case "Planetweb": $version = substr($match[1][0],1,10);
      break;

      case "ICE Browser": $version = substr($match[1][0],1,10);
      break;
    }
    return $version;
  }

  public function showInfo($switch) {
    $switch = strtolower($switch);
    switch($switch){
      case "browser": return $this->info['Browser'];
      break;

      case "os": return $this->info['Operating System'];
      break;

      case "version": return $this->info['Version'];
      break;

      case "all" : return array($this->info["Version"], $this->info['Operating System'], $this->info['Browser']);
      break;

      default: return "Unkonw";
      break;
    }
  }
  public function get_ip() {
  //Just get the headers if we can or else use the SERVER global
    if ( function_exists( 'apache_request_headers' ) ) {
      $headers = apache_request_headers();
    } else {
      $headers = $_SERVER;
    }
    //Get the forwarded IP if it exists
    if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
      $the_ip = $headers['X-Forwarded-For'];
    } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
      $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
    } else {
      $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
    }
    return $the_ip;
  }
}
?>