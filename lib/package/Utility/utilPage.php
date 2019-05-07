<?php
class utilPage{
    /**
     * 
     * Row start
     * @var int
     */
    private $iRowStart = 0;
    
    /**
     * 
     * Row end
     * @var int
     */
    private $iRowEnd = 0;

    /**
     * 
     * Row total
     * @var int
     */
    private $iRowTotal = 0;
    
    /**
     * 
     * Page total
     * @var int
     */
    private $iPageTotal = 0;

    /**
     * 
     * Page current
     * @var int
     */
    private $iPageCurrent = 0;

    /**
     * 
     * Row per page
     * @var int
     */
    
    private $iRowPerPage = 0;

    /**
     * 
     * construct
     * @param int $iRowTotal_
     * @param int $iRowPerPage_
     */
    public function __construct($iRowTotal_, $iRowPerPage_ = 20)
    {
        if(($this->iRowTotal = $iRowTotal_) > 0)
        {
            $this->iRowPerPage = $iRowPerPage_;
            $this->iPageTotal  = ceil($this->iRowTotal / $iRowPerPage_);
        }
    }
    
    /**
     * 
     * Go page
     * @param int $iPage_
     */
    public function GoPage($iPage_ = 1)
    {
        if($this->iRowTotal > 0)
        {
            if($iPage_ == 0 || empty($iPage_)) $iPage_ = 1;
            elseif($iPage_ > $this->iPageTotal) $iPage_ = $this->iPageTotal;
            $this->iPageCurrent = $iPage_;
            $this->iRowStart = ($this->iPageCurrent - 1) * $this->iRowPerPage;
            if($this->iPageCurrent == $this->iPageTotal)
            {
                $this->iRowEnd = $this->iRowTotal;
            }
            elseif ($this->iPageCurrent < $this->iPageTotal)
            {
                $this->iRowEnd = $this->iPageCurrent * $this->iRowPerPage;
            }
        }
    }
	
    /**
     * 
     * Get link html
     * @param unknown_type $destPage_
     * @param unknown_type $param_
     */
    public function getHtml($destPage_ = '', $param_ = '')
    {
    	$strHtml = '';
    	
    	if($this->iPageCurrent <= 5)
    	{
    		$iPageBegin = 1;
    		$iPageEnd = 10;
    	}
    	elseif($this->iPageCurrent >= $this->iPageTotal - 5)
    	{
    		$iPageBegin = ($this->iPageTotal - 9 <= 1) ? 1 : $this->iPageTotal - 9 ;
    		$iPageEnd = $this->iPageTotal;
    	}
    	else 
    	{
			$iPageBegin = $this->iPageCurrent - 4;
			$iPageEnd 	= $this->iPageCurrent + 5;
    	}
		
		for($iPage = $iPageBegin ; $iPage <= $iPageEnd ; $iPage++)
		{
			$classname = ($iPage == $this->iPageCurrent) ? 'btn-info' : '';
			$strHtml .= '
				<a class="btn '.$classname.'" href="'.$destPage_.'?page='.$iPage.'&'.$param_.'">'.$iPage.'</a>
			';
		}
		return $strHtml;
    }
}
?>