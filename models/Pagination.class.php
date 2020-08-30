<?php

class Pagination {
	
	/**
	 * GET parameter for current page.
	 *
	 * @var string
	 */
	protected $paramValue;
	
	/**
	 * Rows per page.
	 *
	 * @var int
	 */
	protected $byPage;
	
	/**
	 * Total number of rows.
	 *
	 * @var int
	 */
	protected $rows;
	
	/**
	 * First button text.
	 *
	 * @var string
	 */
	protected $goFirst = '&laquo;';
	
	/**
	 * Previous button text.
	 *
	 * @var string
	 */
	protected $goPrevious = '&lt;';
	
	/**
	 * Next button text.
	 *
	 * @var string
	 */
	protected $goNext = '&gt;';
	
	/**
	 * Last button text.
	 *
	 * @var string
	 */
	protected $goLast = '&raquo;';
	
	/**
	 * Pagination wrapper HTML.
	 *
	 * @var string
	 */
	protected $paginationWrapper = '<nav><ul class="pagination justify-content-center">%s</ul></nav>';
	
	/**
	 * Disabled page wrapper HTML.
	 *
	 * @var string
	 */
	protected $itemDisabled = '<li class="page-item disabled"><span class="page-link">%s</span></li>';
	
	/**
	 * Available page wrapper HTML.
	 *
	 * @var string
	 */
	protected $itemAvailable = '<li class="page-item"><a class="page-link" href="%s">%s</a></li>';
	
	/**
	 * Active page wrapper HTML.
	 *
	 * @var string
	 */
	protected $itemActive = '<li class="page-item active"><span class="page-link">%s</span></li>';
	
	/**
	 * Avoid duplicate content.
	 *
	 * @var bool
	 */
	protected $avoidDuplicateContent = TRUE;
	
	/**
	 * Constructor.
	 *
	 * @param string GET parameter for current page.
	 */
	public function __construct($paramValue = 'p') {
		$this->currentPage = !empty($_GET[$paramValue]) ? intval($_GET[$paramValue]) : 1;
		$this->paramValue = $paramValue;
	}
	
	/**
	 * Set the number of results per page.
	 *
	 * @param int $byPage
	 * @return this
	 */
	public function setItemsPerPage(int $byPage): Pagination {
		$this->byPage = $byPage;
		return $this;
	}
	
	/**
	 * Set the total page number.
	 *
	 * @param int $rows
	 * @return this
	 */
	public function setTotalRows(int $rows): Pagination {
		$this->rows = $rows;
		return $this;
	}
	
	/**
	 * Set button text for the first page.
	 *
	 * @param string $goFirst
	 * @return this
	 */
	public function setGoFirst(string $goFirst): Pagination {
		$this->goFirst = $goFirst;
		return $this;
	}
	
	/**
	 * Set button text for the previous page.
	 *
	 * @param string $goPrevious
	 * @return this
	 */
	public function setGoPrevious(string $goPrevious): Pagination {
		$this->goPrevious = $goPrevious;
		return $this;
	}
	
	/**
	 * Set button text for the next page.
	 *
	 * @param string $goNext
	 * @return this
	 */
	public function setGoNext(string $goNext): Pagination {
		$this->goNext = $goNext;
		return $this;
	}
	
	/**
	 * Set button text for the last page.
	 *
	 * @param string $goLast
	 * @return this
	 */
	public function setGoLast(string $goLast): Pagination {
		$this->goLast = $goLast;
		return $this;
	}
	
	/**
	 * Set pagination wrapper HTML.
	 *
	 * @param string $paginationWrapper
	 * @return this
	 */
	public function setPaginationWrapper(string $paginationWrapper): Pagination {
		$this->paginationWrapper = $paginationWrapper;
		return $this;
	}
	
	/**
	 * Set disabled page wrapper HTML.
	 *
	 * @param string $itemDisabled
	 * @return this
	 */
	public function setItemDisabled(string $itemDisabled): Pagination {
		$this->itemDisabled = $itemDisabled;
		return $this;
	}
	
	/**
	 * Set available page wrapper HTML.
	 *
	 * @param string $itemAvailable
	 * @return this
	 */
	public function setItemAvailable(string $itemAvailable): Pagination {
		$this->itemAvailable = $itemAvailable;
		return $this;
	}
	
	/**
	 * Set active page wrapper HTML.
	 *
	 * @param string $itemActive
	 * @return this
	 */
	public function setItemActive(string $itemActive): Pagination {
		$this->itemActive = $itemActive;
		return $this;
	}
	
	/**
	 * Set avoid duplicate content.
	 *
	 * @param bool $avoidDuplicateContent
	 * @return this
	 */
	public function setAvoidDuplicateContent(bool $avoidDuplicateContent): Pagination {
		$this->avoidDuplicateContent = $avoidDuplicateContent;
		return $this;
	}
	
	/**
	 * The number of pages.
	 *
	 * return int
	 */
	protected function pagesCount(): int {
		return (int) ceil($this->rows / $this->byPage);
	}
	
	/**
	 * Calculates the first LIMIT number for SQL request.
	 *
	 * return int
	 */
	public function limitFrom(): int {
		if (!isset($this->pagesCount)) {
			$this->pagesCount = $this->pagesCount();
		}
		$from = 0;
		if ($this->currentPage > 0 && $this->rows > 0) {
			$from = $this->currentPage * $this->byPage;
			if ($this->currentPage > 0 && $this->currentPage <= $this->pagesCount) {
				$from = ($this->currentPage - 1) * $this->byPage;
			}
		}
		return $from;
	}
	
	/**
	 * Pagination processing.
	 *
	 * @param int $pageOffset Item offset before and after the current page (default: 4).
	 *
	 * return array
	 *   $array['page']: Button text.
	 *   $array['current']: Boolean marking the current page.
	 *   $array['num']: Page number for the links.
	 *   $array['linked']: Wether button is a link.
	 */
	public function process($pageOffset = 4): array {
		if (!isset($this->pagesCount)) {
			$this->pagesCount = $this->pagesCount();
		}
		if ($this->pagesCount <= 1) {
			return [];
		}
		
		$pages = [];
		if ($this->currentPage > $this->pagesCount) {
			$this->currentPage = $this->pagesCount;
		}
		$pages[] = [
			'page' => $this->goFirst,
			'current' => 0,
			'num' => 1,
			'linked' => $this->currentPage > 1 ? 1 : 0,
		];
		$pages[1] = [
			'page' => $this->goPrevious,
			'current' => 0,
			'num' => $this->currentPage > 1 ? $this->currentPage - 1 : 1,
			'linked' => $this->currentPage > 1 ? 1 : 0,
		];
		if ($this->pagesCount <= 1) {
			$pages[] = [
				'page' => 1,
				'current' => 0,
				'num' => 1,
				'linked' => 1,
			];
		}
		else {
			for ($i = $this->currentPage - $pageOffset, $j = $this->currentPage + $pageOffset; $i <= $j; $i++) {
				if ($i >= 1 && $i <= $this->pagesCount) {
					if ($i == $this->currentPage) {
						$pages[] = [
							'page' => $this->currentPage,
							'current' => 1,
							'num' => $this->currentPage,
							'linked' => 1,
						];
					}
					else {
						$pages[] = [
							'page' => $i,
							'current' => 0,
							'num' => $i,
							'linked' => 1,
						];
					}
				}
			}
		}
		$pages[] = [
			'page' => $this->goNext,
			'current' => 0,
			'num' => $this->currentPage + 1 < $this->pagesCount ? $this->currentPage + 1 : $this->pagesCount,
			'linked' => $this->currentPage < $this->pagesCount && $this->currentPage != $this->pagesCount ? 1 : 0,
		];
		$pages[] = [
			'page' => $this->goLast,
			'current' => 0,
			'num' => $this->pagesCount,
			'linked' => $this->currentPage < $this->pagesCount ? 1 : 0,
		];
		return $pages;
	}
	
	/**
	 * Rendering the pagination.
	 *
	 * @param array $pages The result of $this->process().
	 *
	 * @return string The pagination markup.
	 */
	public function render(array $pages): string {
		if (empty($pages)) {
			return '';
		}
		$qs = $_SERVER['QUERY_STRING'];
		$items = '';
		foreach ($pages as $k => $v) {
			if ($v['current']) {
				$items .= sprintf($this->itemActive, $v['page']);
			}
			elseif (!$v['linked']) {
				$items .= sprintf($this->itemDisabled, $v['page']);
			}
			else {
				if (!$qs) {
					$href = '?' . $this->paramValue . '=' . $v['num'];
				}
				else {
					// Remplacement of paramValue if exists.
					parse_str($qs, $params);
					$params[$this->paramValue] = $v['num'];
					if ($this->avoidDuplicateContent && (1 == $v['num'])) {
						unset($params[$this->paramValue]);
					}
					if (!empty($params)) {
						$href = '?' . http_build_query($params, '', '&amp;');
					}
					else {
						$href = str_replace('?' . $qs, '', $_SERVER['REQUEST_URI']);
					}
				}
				$items .= sprintf($this->itemAvailable, $href, $v['page']);
			}
		}
		return sprintf($this->paginationWrapper, $items);
	}
	
}
