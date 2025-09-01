<?php
if(isset($_GET['path'])):
#1. Get URL 
$url = $_GET['path'];

#2. Clear eache
clearstatcache();

#3. Check path exist or not?
if (file_exists($url)):
    #3.1. Define header information
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($url) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
     header('Content-Length: ' . filesize($url));
    #3.2. Clear System Output butffer
     flush ();
     
     #3.3. Get file size 
     reafiles ($url, true);
     
     #3.4. Terminate
     die ();
     eles:
         echo 'File path does not exitst!';
     endif; // check File exist
     
endif; //check file exit
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Download by Built-in readfile() function</h1>
        <a href="Ex03_Readfile-Download.php?path=files/test.docx">Download file * .docx</a>
        <p><a href="Ex03_Readfile-Download.php?path=files/test.jpg">Download file * .jpg</a>
        <p><a href="Ex03_Readfile-Download.php?path=files/test.pdf">Download file * .pdf</a>
        <p><a href="Ex03_Readfile-Download.php?path=files/test.txt">Download file * .txt</a>
        <p><a href="Ex03_Readfile-Download.php?path=files/test.zip">Download file * .zip</a>
    </body>
</html>