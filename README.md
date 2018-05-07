# WebQBE-Test-project-
## Mysql connection only !

Created with PHP and Ajax.With additional XML and PDF export.

<img src="https://preview.ibb.co/kcbN17/WebQBE.gif" width="400" height="300" />

Note:
Only tested locally.
If you want to delete tmp files you need to give www-data delete permission
	and uncomment the following line in DB.php
	```
	 //unlink($f);//www-data needs delete permission !
	```
	also you can uncomment the following line in the same file for extra security:
	```
	//chmod($file, 0600);
	```

Feel free to play with this mini project.