<?php



class douban {

    var $cookieFile = '/var/www/douban/cookie.tmp';
    var $loginUrl = 'https://www.douban.com/accounts/login';
    var $header = array(
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Charset' => 'GBK,utf-8;q=0.7,*;q=0.3',
        'Accept-Encoding' => 'gzip,deflate,sdch',
        'Accept-Language' => 'zh-CN,zh;q=0.8',
        'Cache-Control' => 'max-age=0',
        'Connection' => 'keep-alive',
        'Content-Length' => '160',
        'Content-Type' => 'application/x-www-form-urlencoded',
    );
    var $loginInfo = array('u' => '', 'p' => '', 'c' => '', 'd' => ''); //u:user  p:password  c:captcha

    //d:captcha-id
    //d:captcha-id
    public function __construct($u = '', $p = '') {
        $this->u = $u;
        $this->p = $p;
    }

    public function setCaptcha($c = '', $d = '') {
        $this->c = $c;
        $this->d = $d;
    }

    public function login() {
        $this->setCaptcha($_POST['c'], $_POST['d']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->loginUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "source=index_nav&form_email={$this->u}&form_password={$this->p}&captcha-solution={$this->c}&captcha-id={$this->d}");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/40.0.2214.111 Chrome/40.0.2214.111 Safari/537.36");
        //get cookie info
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        $html = curl_exec($ch);
        curl_close($ch);


        //if need a captcha ,  I have no idea, so input the code in pic by youself.  what the fuck.......
        if ($this->needCaptcha($html)) {
            $id = $this->getCaptchaId($html);
            $url = "http://www.douban.com/misc/captcha?id=$id&size=s";
            echo "<img src=\"$url\">";
            
            echo "<form action=\"\" method=\"post\"><input name=\"c\" type=\"text\"><input name=\"d\" type=\"hidden\" value=\"$id\"><input type=\"submit\" name=\"realsubmit\"></form>";
            exit;
        }
        return true;
    }

    public function getFriends() {
        //init curl and du some option
        $ch = curl_init('http://www.douban.com/contacts/list');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/40.0.2214.111 Chrome/40.0.2214.111 Safari/537.36");
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
        $result = curl_exec($ch);
        //print_r($result);die();
        curl_close($ch);

        //get the firends name
        preg_match_all('/<h3>[\s\n]+<a[^>]+title=".*">(.*)<\/a>/Us', $result, $m);
        $firends[] = $m[1];

        //if firends more ,  get the page start
        //preg_match_all('/paginator.*start=([0-9]*)"/Us', $result, $p);
          preg_match_all('/start=([0-9]*)"/Us', $result, $p);
        //$pag = array_unique($p[1]);
         $max_start = $p[1][5];
         
         $start = 20;
         $pag = array();
         while ($start<=$max_start) {
             array_push($pag, $start);
             $start += 20;
        }
       // print_r($pag);die();
        //more pages, so we do the same thing in parallel
        if (count($pag) > 0) {
            $arr = array();
            foreach ($pag as $page) {
                $c = curl_init("http://www.douban.com/contacts/list?tag=0&start=$page");
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/40.0.2214.111 Chrome/40.0.2214.111 Safari/537.36");
                curl_setopt($c, CURLOPT_COOKIEFILE, $this->cookieFile);
                curl_setopt($c, CURLOPT_COOKIEJAR, $this->cookieFile);
                array_push($arr, $c);
            }

            $mh = curl_multi_init();
            foreach ($arr as $k => $h) {
                curl_multi_add_handle($mh, $h);
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running > 0);

            foreach ($arr as $k => $h) {
                $new[$k]['data'] = curl_multi_getcontent($h);
                preg_match_all('/<h3>[\s\n]+<a[^>]+title=".*">(.*)<\/a>/Us', $new[$k]['data'], $t);
                $firends[] = $t;
                curl_multi_remove_handle($mh, $h);
            }

            curl_multi_close($mh);
        }
        print_r($firends);die();
        return $firends;
    }

    private function getCaptchaId($html) {
        preg_match('/captcha\-id.*value="(.*)"/Us', $html, $matches);
        return $matches[1];
    }

    private function needCaptcha($html) {
        return (preg_match('/captcha\-id/i', $html)) ? true : false;
    }

}

$douban = new douban('i@zhuchao.org', '2huch@0db');
if ($douban->login()) {
    $r = $douban->getFriends();
    echo "<html lang=\"zh-CN\"><head><meta charset=\"UTF-8\"><title>123</title></head><body>";
    echo '<pre>';
     print_r($r[0]);
    //print_r($r);
    echo '</pre>';
    echo "</body></html>";
    die();
    exit;
}
?>