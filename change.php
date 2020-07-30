<?php
/**
 * Created by PhpStorm.
 * User: liyadong
 * Date: 2020/7/30
 * Time: 6:53 PM
 */


$file1 = "./e1.csv";
$file2 = "./e2.csv";
$file3 = "./e3.csv";
$file4 = "./e5.csv";
$last_data = [];
$a = openFile($file1);
$b = openFile($file2);
$c = openFile($file3);
$d = openFile($file4);

$last_data['精美首饰'] = $a;
$last_data['精致生活'] = $b;
$last_data['变美秘籍'] = $c;
$last_data['小惊喜'] = $d;

function openFile( $file_2 )
{
    $return = [];
    $lines = readYieldFile( $file_2 );

    foreach ( $lines as  $key => $line_2 ) {
        if( $key == 0 )
        {
            continue;
        }

        if( !$line_2 )
        {
            continue;
        }
        $data = explode(',',$line_2);
        $return[] = $data;
//        if( $key % 2 == 1 )
//        {
//            $return[1][] = $data;
//        }
//        if( $key % 2 == 0 )
//        {
//            $return[0][] = $data;
//        }

    }
    return $return;
}


function readYieldFile($fileName){
    $handle = fopen($fileName, 'rb');
    while(!feof($handle)){
        yield fgets($handle);
    }
    fclose($handle);
}
//生成xml文件
$doc = new DOMDocument('1.0','utf8');
$doc->formatOutput = true;
$DOCUMENT = $doc->createElement("DOCUMENT");
$DOCUMENT->setAttribute("content_method",'full');
$item = $doc->createElement("item");

$goods = $doc->createElement("key","测试商品");
$display = $doc->createElement("display");
$url = $doc->createElement("url","http://baidu.com/");
$channel = $doc->createElement("channel");
$value = $doc->createElement("value");

$i = 1;
foreach( $last_data as $key => $data )
{
    $tab_name = $doc->createElement("tab_name",$key);
    $tab_id = $doc->createElement("tab_id",$i);
    $tab_template_id = $doc->createElement("tab_template_id","sku_template_1");
    $value->appendChild($tab_name);
    $value->appendChild($tab_id);
    $value->appendChild($tab_template_id);
    foreach( $data as $val )
    {
        //var_dump($val,urldecode($val['3']),urlencode($val['3']));exit;
        $skus = $doc->createElement("skus");
        $img = $doc->createElement("img",$val['1']);
        $title = $doc->createElement("title",htmlspecialchars($val['2']));
      //  $jump_url = $doc->createElement("jump_url",$val['3']);
        $jump_url = $doc->createElement("jump_url",htmlspecialchars($val['3']));


        $price = $doc->createElement("price",trim($val['4']));
        $origin_price = $doc->createElement("origin_price",trim($val['5']));

        $skus->appendChild($img);
        $skus->appendChild($title);
        $skus->appendChild($jump_url);
        $skus->appendChild($price);
        $skus->appendChild($origin_price);

        $value->appendChild($skus);
       // exit;
    }



    $i++;
}
$display->appendChild($url);
$display->appendChild($channel);
$display->appendChild($value);


$item->appendChild($goods);
$item->appendChild($display);

$DOCUMENT->appendChild($item);
$doc->appendChild($DOCUMENT);

$xml_string = $doc->saveXML();
file_put_contents("test.xml", $xml_string);

echo '写入成功';