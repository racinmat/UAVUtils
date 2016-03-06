<?php


$str="abcdefgh"."efghefgh";
$imax=1024/strlen($str)*1024*4;      # 4mb

$starttime=time();
print("exec.tm.sec\tstr.length\tmemory allocated\tmemory used\n");

$gstr='';
$i=0;

while($i++ < $imax+1000){

	$gstr.=$str;
	$gstr=preg_replace('/efgh/','____',$gstr);
	$lngth=strlen($str)*$i;
	if($lngth % (1024*256)==0){
		print (time()-$starttime."sec\t\t".($lngth/1024)."kb\t\t". (memory_get_usage(true)/1024)."\t\t\t\t".(memory_get_usage() / 1024)."\n");
	}
}
