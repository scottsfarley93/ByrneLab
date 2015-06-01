<?php
$output_format = "pdf";
if(isset($_POST['data'])){
	$data = $_POST['data'];
}else{
	die("Couldn't fetch download payload.");
}
$time = time();
$input_file = tmpfile();
$filename = $time . ".pdf";
$output_file = fopen($filename, 'w');
fwrite($input_file, $data);
$zoom = 1;
system("rsvg-convert -o '$input_file' >'$output_file'");
//header("Content-type: application/pdf");
//header("Content-Disposition:attachment;filename='" . $filename . "'");
$pdf_data = readfile($filename);
echo $pdf_data;
?>
