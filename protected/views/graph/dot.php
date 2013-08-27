<?php
$rand = mt_rand(0, 9999999);
$graphname = 'graph_'.date('Y.m.d').'_'.$rand.'.gv';

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$graphname);
header("Content-Type: application/x-graphviz");
echo $graph->parse();
