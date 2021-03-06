<?php

function arr_month($month=0) {
    $arr = array(
        1=>_('Январь'),
        2=>_('Февраль'),
        3=>_('Март'),
        4=>_('Апрель'),
        5=>_('Май'),
        6=>_('Июнь'),
        7=>_('Июль'),
        8=>_('Август'),
        9=>_('Сентябрь'),
        10=>_('Октябрь'),
        11=>_('Ноябрь'),
        12=>_('Декабрь'),
    );
    
    if($month)
    {
        return $arr[$month];
}
    else
    {
        return $arr;
    }

}

function arr_month2($month=0) {
    $month = (int)$month;
    $arr = array(
        1=>_('Января'),
        2=>_('Февраля'),
        3=>_('Марта'),
        4=>_('Апреля'),
        5=>_('Мая'),
        6=>_('Июня'),
        7=>_('Июля'),
        8=>_('Августа'),
        9=>_('Сентября'),
        10=>_('Октября'),
        11=>_('Ноября'),
        12=>_('Декабря'),
    );
    
    if($month)
    {
        return $arr[$month];
    }
    else
    {
        return $arr;
    }
    
}

function week_day($day)
{
    $arr = array(
        0=>_('Воскресенье'),
        1=>_('Понедельник'),
        2=>_('Вторник'),
        3=>_('Среда'),
        4=>_('Четверг'),
        5=>_('Пятница'),
        6=>_('Суббота'),
    );
    
    return $arr[$day];
}

/**
 *
 * @param type $start_year начало отсчета
 * @param type $end конец отсчета
 * @param type $revers в обратную сторону
 * @return array 
 */
function arr_years($start_year = 2010, $end = '',$revers = false) {
    $arr = array();
    $now = $end ? $end:date('Y');
    if(!$revers)
        for($i=$start_year; $i<=$now;$i++)
            $arr[$i] = $i;
    else
        for($i=$now; $i>=$start_year;$i--)
            $arr[$i] = $i;
    return $arr;
}

function arr_days($end_day = 31) {
    $arr = array();;
    for($i=1; $i<=$end_day;$i++)
        $arr[$i] = $i;
    return $arr;
}

/**
 *
 * @param array $arr массив ключ-значение, значение - описание
 * @param str $value  выбранный элемент
 * @param str $htmlOpt html опции
 * @return string 
 */
function getOptions($arr,$value='',$htmlOpt='')
{
    $str = '';
    foreach ($arr as $k=>$v) 
    {
        if($value)
            $str .= '<option value="'.$k.'" '.($k==$value ? 'selected':'').' '.($htmlOpt ? $htmlOpt:'').' >'.$v.'</option>';
        else
            $str .= '<option value="'.$k.'" '.($htmlOpt ? $htmlOpt:'').'>'.$v.'</option>';
    }
    
    return $str;
}

function parse_date($date){
  $ndate=explode(".",$date);
  $rdate=mktime(0,0,0,$ndate[1],$ndate[0],$ndate[2]);
  return $rdate;
}

function pluralForm($n, $form1, $form2, $form5){
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;
    return $form5;
}

class anti_mate {
  var $let_matches = array (
    "a" => "а",
    "c" => "с",
    "e" => "е",
    "k" => "к",
    "m" => "м",
    "o" => "о",
    "x" => "х",
    "y" => "у",
    "ё" => "е"
  );

  //bad words array. Regexp's symbols are readable !
  var $bad_words = array (".*ху(й|и|я|е|л(и|е)).*", ".*пи(з|с)д.*", "бля.*", ".*бля(д|т|ц).*", "(с|сц)ук(а|о|и).*", "еб.*", ".*уеб.*", "заеб.*", ".*еб(а|и)(н|с|щ|ц).*", ".*ебу(ч|щ).*", ".*пид(о|е)р.*", ".*хер.*", "г(а|о)ндон", ".*залуп.*");

  function rand_replace (){
    $output = " (ой!) ";
    return $output;
  }

  function filter ($string){

    global $kernel;
    /* @var $q query_mysql */
    $q = $kernel['db']->query();

    $q->query("select lower(name) as name from badwords");
    $args['items']=$q->get_allrows();
    foreach($args['items'] as $i){
      $this->bad_words[]=strtolower($i['name']);
    }

    $counter = 0;
    $elems = explode (" ", $string); //here we explode string to words
    $count_elems = count($elems);
    for ($i=0; $i<$count_elems; $i++){
      $blocked = 0;
      /*formating word...*/
      $str_rep = eregi_replace ("[^a-zA-Zа-яА-Яё]", "", mb_strtolower($elems[$i],'cp1251'));
      for ($j=0; $j<strlen($str_rep); $j++){
        foreach ($this->let_matches as $key => $value){
          if ($str_rep[$j] == $key)
          $str_rep[$j] = $value;
        }
      }

      /*done*/

      /*here we are trying to find bad word*/
      /*match in the special array*/
      for ($k=0; $k<count($this->bad_words); $k++){
        if (eregi("\*$", $this->bad_words[$k])){
          if (eregi("^".$this->bad_words[$k], $str_rep)){
            $elems[$i] = $this->rand_replace();
            $blocked = 1;
            $counter++;
            break;
          }
        }

        if ($str_rep == $this->bad_words[$k]){
          $elems[$i] = $this->rand_replace();
          $blocked = 1;
          $counter++;
          break;
        }
      }
    }
    if ($counter != 0)
    $string = implode (" ", $elems); //here we implode words in the whole string

    return $string;
  }
}


function getIP()
{
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function translit($text)
{
  static $map = array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e',
                      'ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'y','к'=>'k',
                      'л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r',
                      'с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c',
                      'ч'=>'ch','ш'=>'sh','щ'=>'sh','ъ'=>'','ы'=>'y','ь'=>'',
                      'э'=>'e','ю'=>'yu','я'=>'ya',
                      'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E',
                      'Ё'=>'YO','Ж'=>'ZH','З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K',
                      'Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R',
                      'С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C',
                      'Ч'=>'CH','Ш'=>'SH','Щ'=>'SH','Ъ'=>'','Ы'=>'Y','Ь'=>'',
                      'Э'=>'E','Ю'=>'YU','Я'=>'YA');
  static $keys = NULL;
  if($keys===NULL) { $keys = array_keys($map); }
  static $values = NULL;
  if($values===NULL) { $values = array_values($map); }
  return str_replace($keys, $values, $text);
}

function lastmod($uri, $etag)
{
  $lastmod = time();
  global $kernel;
  /* @var $q query_mysql */
  $q = $kernel['db']->query();
  $q->format("SELECT etag,lastmod FROM lastmod WHERE uri='%s'", $uri);
  $r = $q->get_row();
  $q->free_result();
  if(empty($r)) { $q->format("INSERT INTO lastmod SET uri='%s',etag='%s',lastmod='%d'", $uri, $etag, $lastmod); }
  else
  {
    // touch
    if($etag!=$r['etag'])
    {
      $lastmod = time();
      $q->format("UPDATE lastmod SET etag='%s',lastmod='%d' WHERE uri='%s'", $etag, $lastmod, $uri);
    }
    else { $lastmod = $r['lastmod']; }
    // only If-Modified-Since:
    if(!isset($_SERVER['HTTP_IF_NONE_MATCH']) && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
    {
      $ifms = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
      $ifms = preg_replace('~GMT.*$~', 'GMT', $ifms);
      $ifms = strtotime($ifms);
      if($ifms >= $lastmod) { $lastmod = NULL; }
    }
    // only If-None-Match:
    elseif(isset($_SERVER['HTTP_IF_NONE_MATCH']) && !isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
    {
      $ifnm = explode(',', $_SERVER['HTTP_IF_NONE_MATCH']);
      foreach($ifnm as $i)
      {
        if(trim($i)=="\"$etag\"") { $lastmod = NULL; break; }
      }
    }
    // If-Modified-Since AND If-None-Match:
    elseif(isset($_SERVER['HTTP_IF_NONE_MATCH']) || isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
    {
      $ifms = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
      $ifms = preg_replace('~GMT.*$~', 'GMT', $ifms);
      $ifms = strtotime($ifms);
      $ifnm = explode(',', $_SERVER['HTTP_IF_NONE_MATCH']);
      if($ifms >= $lastmod)
      {
        foreach($ifnm as $i)
        {
          if(trim($i)=="\"$etag\"") { $lastmod = NULL; break; }
        }
      }
    }
  }
  return $lastmod;
}

function notags($text)
{
  $text = str_replace('<', '&lt;', $text);
  $text = str_replace('>', '&gt;', $text);
  return $text;
}


function months($short=false)
{
  $ret = array();
  $format = ($short? '%b' : '%B');
  for($i = 0; $i < 12; $i++) { $ret[ $i + 1 ] = strftime($format, strtotime("+$i month", 1)); }
  return $ret;
}


// protected vars environment
function module($path, $params=array(), $buf=false, $extr=false)
{
  global $kernel;
  // extract module name
  $name = preg_replace('~(\w+)/(.*)~', '\1', $path);
  // run-time params
  if(!is_array($params)) { $params = array(); }
  // local params
  if(is_array($kernel['params'][ $name ])) { $params = array_merge($kernel['params'][ $name ], $params); }
  // global params
  if(is_array($kernel['params'][ 0 ])) { $params = array_merge($kernel['params'][0], $params); }
  // extract params
  if($extr && is_array($params)) { extract($params, EXTR_REFS|EXTR_PREFIX_SAME, 'p_'); }
  // find path
  $path = MODULES_DIR. '/'. $path;
  if(!file_exists($path)) { trigger_error("Load module #${path} failed!", E_USER_ERROR); }
  // load config
  $config = array();
  $dir = dirname($path);
  if(is_file($dir.'/config.php'))
  {
    $ret = include($dir. '/config.php');
    if(is_array($ret)) { $config = $ret; }
    unset($ret);
  }
  // output buffering
  if($buf)
  {
    ob_start();
    include($path);
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
  }
  else { return include($path); }
}


function http_redirect($uri, $header=true)
{
  setcookie(session_name(), session_id());
  session_write_close();
  if(!$uri)
  {
    $uri = $_SERVER['PHP_SELF'];
    if(!$uri) { $uri = '/'; }
  }
  if($header && $uri) { header('Location: '. $uri); }
  echo '<html><head><meta http-equiv="refresh" content="3; url='. trim($uri). '" /></head><body>';
  echo '<table width="100%" height="100%"><tr valign="center"><td align="center"><a href="'. trim($uri). '">click to redirect</a></td></tr></table>';
  echo '</body></html>';
  flush();
  exit(0);
}

// protected vars environment
function template($path, $args=array(), $errors=array(), $buf=false, $extr=false)
{
  global $kernel;
  if(!file_exists($path)) { trigger_error("Load template #${path} failed!", E_USER_WARNING);return; }
  if($extr)
  {
     if(is_array($args)) { extract($args, EXTR_REFS|EXTR_PREFIX_SAME, 'a_'); }
     if(is_array($errors)) { extract($errors, EXTR_REFS|EXTR_PREFIX_ALL, 'e_'); }
  }
  if($buf)
  {
    if(is_string($buf)) { ob_start($buf); }
    else { ob_start(); }
  }
  $ret = include($path);
  if($buf)
  {
    $ret = ob_get_contents();
    ob_end_clean();
  }
  return $ret;
}

function setfileperm($path)
{
  $ret = true;
  global $kernel;
  if($kernel['config']['chmod'])
   { $ret = ($ret && @chmod($path, $kernel['config']['chmod'])); }
  if($kernel['config']['chown'])
   { $ret = ($ret && @chown($path, $kernel['config']['chown'])); }
  return $ret;
}

function lockwrite($path, $content)
{
  $ret = NULL;
  if($f = fopen($path, 'w'))
  {
    if (flock($f, LOCK_EX))
    {
      fwrite($f, $content);
      flock($f, LOCK_UN);
      $ret = true;
    }
    fclose($f);
  }
  return $ret;
}

function lockread($path)
{
  $ret = NULL;
  if($f = fopen($path, 'r'))
  {
    if (flock($f, LOCK_SH))
    {
      while (!feof($f))
      {
        $ret.= fread($f, 8192);
      }
      flock($f, LOCK_UN);
    }
    fclose($f);
  }
  return $ret;
}

function array2query($in)
{
  return str_replace('&amp;', '&', $in);
}

function query2array($in)
{
  $ret = array();
  parse_str(str_replace('&amp;', '&', $in), $ret);
  return $ret;
}

function maket($id, $args=array())
{
  global $kernel;
  $q = $kernel['db']->query();
  $q->query(sprintf("SELECT updated, file FROM makets WHERE id='%d'", $id),true);
  $r = $q->get_row();
  $q->free_result();

  if(!empty($r))
  {
    if(isset($r['file']) && $r['file'])
    {
        $path = $_SERVER['DOCUMENT_ROOT'].'/kernel/templates/'.$r['file'];
    }
    else 
    {
        $mtime = NULL;
        $path = TEMP_CACHE_DIR. '/template#'. $id. '.phpt';
        if(file_exists($path)) { $mtime = filemtime($path); }
        if($mtime && $mtime==$r['updated']) { } // db == file, fast mode
        elseif(!$mtime || $r['updated'] > $mtime) // db -> file
        {
          $q->query(sprintf("SELECT content,updated FROM makets WHERE id='%d'", $id));
          $r = $q->get_row();
          $q->free_result();
          if(!empty($r) && lockwrite($path, $r['content']))
          {
            if($r['updated']) { touch($path, $r['updated']); }
          }
        }
    }
    return template($path, $args);
    
  }
  return NULL;
}

function show404()
{
    global $kernel;
    $kernel['http_code'] = 404;
    return '';
}

/**
 * Получение одной ветви дерева
 * $arr - массив вида
 * $arr[$id] = array('parent_id'=>...,'name'=>'value',....)
 * **/
function getTreeBranch($arr,$id,$out){
    if(isset($arr[$id]))
    {
        $out[] = $arr[$id];
        if($arr[$id]['parent_id'] != 0)
        {
            $out = getTreeBranch($arr,$arr[$id]['parent_id'],$out);
        }
    }
    return $out;
}

function make_password($num_chars) {
    if ((is_numeric($num_chars)) &&
            ($num_chars > 0) && (!is_null($num_chars))) {
        $password = '';
        $accepted_chars = 'abcdefghijklmnopqrstuvwxyzl234567890'; // Seed the generator if necessary. srand(((int)((double)microtime()*1000003))  );
        for ($i = 0; $i < $num_chars; $i++) {
            $random_number = rand(0, (strlen($accepted_chars) - 1));
            $password .= $accepted_chars[$random_number];
        }
        return $password;
    }
}

/**
 * Путь для картинок с ресайзом или без
 * @param str $name имя картинки
 * @param bool $resize true - с ресайзом, false - без ресайза
 * @param int $w ширина
 * @param int $h высота
 * @param str $type тип ресайза thumb,crop,resize,i_resize,resize_png,thumb_png
 */
function getImgPath($name, $resize, $w=0, $h=0, $type='', $water='')
{
    $str = '';
    if(!$resize)
    {
        $str = '/upload/images/'.$name;
    }
    else
    {
        $path = $_SERVER['DOCUMENT_ROOT'].'/kernel/cache/images/image__upload_images_'.$name.'_'.$type.'_'.$w.'x'.$h.'_FFFFFF_100_'.($water ? 1:0).'.jpg';
        if(file_exists($path))
        {
            $str = '/cacheimg/'.$w.'/'.$h.($water ? '/1':'').($type ? '/'.$type:'').'/'.$name;
    }
        else 
        {
            $str = '/getimg/'.$w.'/'.$h.($water ? '/1':'').($type ? '/'.$type:'').'/'.$name;
        }
        
    }
    return $str;
}

function get_web_page($url, $postdata = '', $header = array())
{
    $uagent = "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // ?????????? ???-????????
    curl_setopt($ch, CURLOPT_HEADER, 0);           // ?? ?????????? ?????????
    if ($header)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);   // ????????? ?? ??????????
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 220); // ??????? ??????????
    curl_setopt($ch, CURLOPT_TIMEOUT, 220);        // ??????? ??????
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // ??????????????? ????? 10-??? ?????????
    if ($postdata)
    {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    }
    curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/coo.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . "/coo.txt");
    
    $content = curl_exec($ch);
    $header = curl_getinfo($ch);

    curl_close($ch);
    return $content;
}

function addJs($js)
{
    global $kernel;
    if(!isset($kernel['js']) || !in_array($js, $kernel['js']))
    {
        $kernel['js'][] = $js;
    }
    return true;
}

function getJs()
{
    global $kernel;
    $str = '';
    if($kernel['js'])
    {
        foreach ($kernel['js'] as $v)
        {
            $str .= '<script type="text/javascript" src="/js/'.$v.'"></script>'."\n";
        }  
    }
    return $str;
}

function addCss($js)
{
    global $kernel;
    if(!isset($kernel['css']) || !in_array($js, $kernel['css']))
    {
        $kernel['css'][] = $js;
    }
    return true;
}

function getCss()
{
    global $kernel;
    $str = '';
    if($kernel['css'])
    {
        foreach ($kernel['css'] as $v)
        {
            $str .= '<link href="/css/'.$v.'" rel="stylesheet" type="text/css" />'."\n";
        }  
    }
    return $str;
}

function seturl($link)
{
    global $kernel;
    return ($kernel['lng'] != 'ru' ? '/'.$kernel['lng']:'').$link;
}

function lngPostfix()
{
    global $kernel;
    return ($kernel['lng'] != 'ru' ? '_'.$kernel['lng']:'');
}



/**
 * Проверяет наличие актуального кеш файла
 * @param type $url request uri
 * @param type $time time in seconds
 * @return boolean
 */
function  checkCache($url, $type='cache', $time=1800){
    $mtime = NULL;
    $path = HTDOC_CACHE_DIR. '/htdoc#'. urlencode($url). '_'.$type.'.html';
    if(file_exists($path)) { $mtime = filemtime($path); }
    if($mtime && ($mtime+$time)>=time()) { 
        include($path);
        return true;
    } // если время совпадает то все норм
    elseif(!$mtime || ($mtime+$time)<time()){ // иначе пишем из базы в файл
        return false;
    }
}
?>
