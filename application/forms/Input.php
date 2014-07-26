<?php

class Application_Form_Input extends Zend_Form
{

	public function init()
	{
		// Set the method for the form to POST
	$this->setMethod('post');
		
		// Set this form as a File Upload form
		$this->setAttrib('enctype', 'multipart/form-data');
		
		// Add an text element for the Email Address
		$email = new Zend_Form_Element_Text('text');
		
		// Specify Label for the field
		$email->setLabel('Your email address:')
    	  ->setRequired(true);
		
		// Add validation for the form
		$email->addValidator('NotEmpty', true)
			->addValidator('EmailAddress', true)
			->addFilter('StringTrim');
		
		// Customize Error Messages	
		$email->getValidator('NotEmpty')
			 ->setMessage('Please specify an Email Address');
		$email->getValidator('EmailAddress')
			 ->setMessage('Please type a valid Email Address');
		
		// Add this element to Form
		$this->addElement($email, 'text');
		
		
		// Create a new File Form Element
		$file = new Zend_Form_Element_File('file');
		$file->setLabel('CSV File:')
			->setDestination(sys_get_temp_dir())
    		->setRequired(true);
		
		// Ensure a file is always selected to upload		
		$file->addValidator('File_Upload', true);
		
		// ensure only 1 file
		$file->addValidator('Count', true, 1);
		
		// only CSV Files
		$file->addValidator('Extension', true, 'csv');
		
		//Customize Error Messages
		$file->getValidator('File_Upload')
			 ->setMessage('Please select a CSV File');
		$file->getValidator('Count')
			 ->setMessage('Select only 1 File');
		$file->getValidator('Extension')
			 ->setMessage('Only CSV files are allowed');
			 
		// Add this element to Form	 
		$this->addElement($file, 'file');
 	 
			 
	// Add the submit button
	$this->addElement('submit', 'submit', array(
		'ignore' => true,
		'label' => 'Upload File',
		));
	}


}

