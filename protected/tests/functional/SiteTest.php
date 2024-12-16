<?php

//Abhinandan. Added this bootstrap..
require_once(dirname(__FILE__).'/../bootstrap.php');   


 /*
 *  Abhinandan. Let SiteTest extend its child concrete class 'WebTestCase'..
  *         Let 'TEST_BASE_URL' be defined inside 'WebTestCase'...
 * 
 */
class SiteTest extends WebTestCase
{
	public function testTitle(){	
     $this->open( TEST_BASE_URL );
     $this->assertTitle('Helios - Site');	
    }
	
	
	public function testIndex()
	{
	 $this->open( TEST_BASE_URL );
	 $this->assertTextPresent('Welcome');
	}
	
	
	public function testAbout()
	{
	 $this->open( TEST_BASE_URL . 'site/page?view=about' );
	 $this->assertTextPresent('about');
	}
	
	public function testHomeAboutSearchLoginHeaderBar(){
	 $home_page      = TEST_BASE_URL;
	 $about_page     = TEST_BASE_URL . 'site/page?view=about';
	 $info_pages_arr = array( $home_page, $about_page );
	 
	 for($i=0; $i<count($info_pages_arr); ++$i){  //Ensure all "info" pages come equipped with standard Header Bar..
	  $this->open( $info_pages_arr[$i] );	
	  $this->assertElementPresent("//a[@href='/Helios_Mar_12th']");
	  $this->assertElementPresent("//a[@href='/Helios_Mar_12th/site/page?view=about']");
	  $this->assertElementPresent("//input[@placeholder='search']");	
	  $this->assertElementPresent("//a[@href='/Helios_Mar_12th/mportal']");	
	 } //end for i..
	}
	
	
	public function testLoginValidation()
	{
	 //Abhinandan. Test Login validation..
	 $this->open( TEST_BASE_URL );
	
	 if($this->isTextPresent('login'))
	 {
	  $this->clickAndWait( "//a[@href='/Helios_Mar_12th/mportal']" );
	  $this->assertElementPresent('name=UserGroupsUser[username]');
	  $this->assertElementPresent('name=UserGroupsUser[password]');  
	  $this->click("//button[@type='submit']"); 
	  $this->waitForTextPresent('Please complete this mandatory field.');	
	 }
	} //end testLoginLogout()..
	
} //end SiteTest class..
