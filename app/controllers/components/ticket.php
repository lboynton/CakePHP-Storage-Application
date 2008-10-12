<?php
App::import('Model', 'Ticket');

// http://bakery.cakephp.org/articles/view/ticket-component-resetting-user-passwords
class TicketComponent
{
    // Create a new ticket by providing the data to be stored in the ticket.
    function set($info = null)
    {
        $this->garbage();
        if ($info)
        {
            $ticketObj = new Ticket();
            $data['Ticket']['hash'] = md5(time());
            $data['Ticket']['data'] = $info;

            if ($ticketObj->save($data))
            {
                return $data['Ticket']['hash'];
            }
        }
        return false;
    }
    
    // Return the value stored or false if the ticket can not be found.
    function get($ticket = null)
    {
        $this->garbage();
        if ($ticket)
        {
            $ticketObj = new Ticket();
            $data = $ticketObj->findByHash($ticket);
            if (is_array($data) && is_array($data['Ticket']))
            {
                // optionally auto-delete the ticket -> this->del($ticket);
                return $data['Ticket']['data'];
            }
        }
        return false;
    }

    // Delete a used ticket
    function del($ticket = null)
    {
        $this->garbage();
        if ($ticket)
        {
            $ticketObj = new Ticket();
            $data = $ticketObj->findByHash($ticket);
            if ( is_array($data) && is_array($data['Ticket']) )
            {
                return $data = $ticketObj->del($data['Ticket']['id']);
            }
        }
        return false;
    }
	
	// delete a ticket using the data as a reference
	function delByData($data = null)
	{
        $this->garbage();
        if ($data)
        {
            $ticketObj = new Ticket();
            $data = $ticketObj->findByData($data);
            if ( is_array($data) && is_array($data['Ticket']) )
            {
                return $data = $ticketObj->del($data['Ticket']['id']);
            }
        }
        return false;
	}

    // Remove old tickets
    function garbage()
    {        
        $deadline = date('Y-m-d H:i:s', time() - (24 * 60 * 60)); // keep tickets for 24h.
        $ticketObj = new Ticket();
        $data = $ticketObj->query('DELETE from tickets WHERE created < \''.$deadline.'\'');
    }
}
?>