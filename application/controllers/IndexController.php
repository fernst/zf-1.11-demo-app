<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $request = $this->getRequest();
        $csvForm = new Application_Form_Input();
		
		//List of errorsm user to gracefully display application errors
		$errors = array();
 		
		//If a form has been submitted, attempt to process CSV File.
		if ($this->getRequest()->isPost()) {
			//Check if Form was validated successfully
			if ($csvForm->isValid($request->getPost())) {
				
				//Get the Email Address from the Form
				$email = $csvForm->getValue('text');
				
				//Check if file was received successfully. If not, application ends with an error message.
				if (!$csvForm->file->receive()) {
				    $errors[] = "Error Receiving the File";
				} else {										
					//Get File location
					$location = $csvForm->file->getFileName();				
					try {
						//Open and read first line of file, looking for the Firstname column.					
						$file = fopen($location,"r");
						
						//If the file was not opened successfully, display an error
						if ($file===false) {
							throw new Exception('Error reading uploaded file');
						};
						
						//Read headers from the CSV file and find the Firstname key.
						$headers = fgetcsv($file);
						$key = array_search('Firstname',$headers);
						
						//If the First Name key is not found, file format is not valid.
						if ($key===false) {
							throw new Exception('File Format is not valid');
						};
						
						//Fill an array with the list of people. Also, get the list of first names for sorting.
						$peopleList = array();	
						$sortIndex = array();
						
						//Populate peopleList array with the remaining CSV Lines.
						while(!feof($file))
						{
							$line = fgetcsv($file);
							if ($line===false) continue;
							$peopleList[] = $line;
							$sortIndex[] = strtolower($line[$key]);
						}
						
						//Sort the peopleList array using the list of names in ascending order.
						array_multisort(
							$sortIndex,SORT_ASC,$peopleList
						);
						
						//Close the file.
						fclose($file);
						
						//Set the view variables
						$this->view->email = $email;
						$this->view->peopleList = $peopleList;
						
						//Render the result view.
						$this->_helper->viewRenderer('index/result', null, true);
						
					} catch (Exception $e) {
						$errors[] = "Error processing the file: ".$e->getMessage();
					}
				}	
			}
		}
 
		//Set the form and the error array for the index view.
		$this->view->form = $csvForm;
		$this->view->errors = $errors;
    }

}

