<?php


  try {
            $ws = "http://106.37.176.134:9527/WebServiceRAA.asmx?wsdl";
            $client = new SoapClient(
                $ws, array(
                'trace' => '0',
                'location' => "http://106.37.176.134:9527/",
                'uri' => "http://106.37.176.134:9527/"
                )
            );
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = false;
            $client->xml_coding = 'utf-8';

            $param = array(
                'pagesize' => $size,
                'page' => 1,
                'datetime' => $date
            );


            $res = $client->GetResumeStorelist($param);
            //var_dump($res);die();
            $resRes = $res->GetResumeStorelistResult;
            //var_dump($resRes);die();
            $arrResume = json_decode($resRes, true);
           // print_r($arrResume);
            //$path = '/opt/tmp/';
            foreach   ($arrResume as $k => $v) {
                //print_r($v['RS_ResumeDetail']);
                //echo  $k;
                //$file = $path . $v['RS_Name'] . '.txt';
                $file = $path . $v['RS_Name'] . '-' . $v['RS_Tel'].'.txt';
                //print_r($file);
                //var_dump($v['RS_ResumeDetail']);
                // print_r($v['D_Name']);
               // print_r($v);die();
                $resumeInfo = $v;
                $result = $this->model('common/Model_common')->uploadFile( array( 'ext'=>'txt', 'content'=>$v['RS_ResumeDetail']) );

                if ( !empty( $result['response']['err_no']) || empty($result['response']['results']['filename']) || empty($result['response']['results']['groupname'])) {
                    $this->log->push_info('importResumeForIsoftstone dfs fail. result: %s', array(print_r($result, true)));
                    throw new Exception($this->config->item(150104, 'err_msg'), 150104);
                }
                //跟踪日志：成功存储到DFS
               // $this->log->push_info('importResumeForIsoftstone->step1 success. at:%s, uid:%s,parent_id:%s, local:%s, remote:%s, groupname:%s', array( date('Y-m-d H:i:s'), $postData['uid'], $parent_id, $name, $result['response']['results']['filename'], $result['response']['results']['groupname'] ) );
               
                unset($resumeInfo['RS_ResumeDetail']);
  
                try {
                    
                    $token = 'KA-Api';
                    $otoken = 'KA-Api';
                    $extension = 'txt';
                    $uid = 29;
                    $parent_id = 29;

                    //调用自己的work，进行异步通知简历解析
                    $param = array(
                        'ext' => $extension, //格式要与上传到DFS上的文件格式一致（即与uploadFile方法的参数ext一致）
                        'appid' => 5, //TOB
                        'uid' => $uid,
                        'token' => $token,
                        'otoken' => $otoken,
                        'localname' => $resumeInfo['RS_Name'] . '.txt',
                        'info' => $resumeInfo,
                        'filename' => $result['response']['results']['filename'],
                        'groupname' => $result['response']['results']['groupname'],
                        'parent_id' => $parent_id
                    );
                    //print_r($param);die();
                    $this->model('common/Model_common')->sendParseNoticeForApi($param);
                } catch (Exception $e) {
                    //调用自己的work（sendParseNotice触发）异常
                    $this->log->push_info('importResumeForIsoftstone sendParseNotice worker fail.', array());
                    throw new Exception($this->config->item(150104, 'err_msg'), 150104);
                }
?>
