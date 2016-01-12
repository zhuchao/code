<?php 
        //获取文件夹中文件数量
        function getfilecounts($dir) {
            $handle = opendir($dir);
            $i = 0;
            while (false !== $file = (readdir($handle))) {
                if ($file !== '.' && $file != '..') {
                    $i++;
                }
            }
            closedir($handle);
            return $i;
        }

	$target = '/opt/tmp/resume';
	//遍历所有目录
	if(is_dir($target)){  
            $dirHandle = opendir($target);
            while (false !== ($fileName = readdir($dirHandle))) {
                //$target = rtrim($target, '//');
                $subPath = $target . DIRECTORY_SEPARATOR . $fileName;
                
                if ( is_dir( $subPath) && str_replace('.', '', $fileName) != '') {

                    $count = getfilecounts($subFile);
                    $n += $count;
                    echo  $subFile, ' -> ', $count,  "\n";
 
                }
         }
         echo 'total:', $n;

        closedir($dirHandle);

?>
