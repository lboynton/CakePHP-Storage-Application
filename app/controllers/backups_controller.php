<?php 
class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form');
	
	function beforeFilter()
    {
		// require all functions to be authenticated
        $this->__validateLoginStatus();
    }
		
	function restore()
	{
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->pageTitle = "Restore";
		
		$this->set('backups', $this->Backup->find('all', array('conditions' => array('user_id' => $this->Session->read('User.id')))));
	}
	
	/**
	 * Add file to backup
	 */
	 function add() 
	 {
	 	$this->pageTitle = "Backup";
		
        if (!empty($this->data) &&
             is_uploaded_file($this->data['Backup']['File']['tmp_name'])) 
		{
            $fileData = fread(fopen($this->data['Backup']['File']['tmp_name'], "r"),
                                     $this->data['Backup']['File']['size']);

            $this->data['Backup']['name'] = $this->data['Backup']['File']['name'];
            $this->data['Backup']['type'] = $this->data['Backup']['File']['type'];
            $this->data['Backup']['size'] = $this->data['Backup']['File']['size'];
            $this->data['Backup']['data'] = $fileData;
			$this->data['Backup']['md5sum'] = md5($fileData);
			$this->data['Backup']['user_id'] = $this->Session->read('User.id');

            $this->Backup->save($this->data);

            $this->redirect('/users');
        }
    }
	
	function download($id) 
	{
		Configure::write('debug', 0);
		$file = $this->Backup->findById($id);
	
		header('Content-type: ' . $file['Backup']['type']);
		header('Content-length: ' . $file['Backup']['size']);
		header('Content-Disposition: attachment; filename="'.$file['Backup']['name'].'"');
		echo $file['Backup']['data'];
	
		exit();
	}
}
?>