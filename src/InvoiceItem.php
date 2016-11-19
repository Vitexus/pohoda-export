<?php
/**
 * Author: Ivo Toman
 */

namespace Pohoda;


class InvoiceItem
{
	private $text;
	private $quantity = '1.0';
	private $unit;
	private $coefficient = '1.0';
	private $payVAT = false; //pouze textove polozky
	private $rateVAT;
	private $percentVAT;
	private $discountPercentage;
	private $homeCurrency = [
		"unitPrice" => null,
		"price" => null,
		"priceVat" => null,
		"priceSum" => null,
	];
	private $foreignCurrency = [
		"unitPrice" => null,
		"price" => null,
		"priceVat" => null,
		"priceSum" => null,
	];
	private $note;
	private $code;
	private $guarantee = 48;
	private $guaranteeType = "months";

	private $stockItem; //odkaz na skladovou zasobu


	/**
	 * @return string $text
	 */
	public function getText()
	{
		return $this->text;
	}


	public function setText($text)
	{
		if (Validators::isMaxLength($text, 90) === false)
			$text = mb_substr($text, 0, 90);
		$this->text = $text;
	}

	/**
	 * @return float
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}


	/**
	 * @param float $quantity
	 */
	public function setQuantity($quantity)
	{
		Validators::isNumeric($quantity);
		$this->quantity = $quantity;
	}


	/**
	 * @return string $unit
	 */
	public function getUnit()
	{
		return $this->unit;
	}

	public function setUnit($unit)
	{
		Validators::assertMaxLength($unit, 10);
		$this->unit = $unit;
	}

	/**
	 * @return float
	 */
	public function getCoefficient()
	{
		return $this->coefficient;
	}

	/**
	 * @param float $coefficient
	 */
	public function setCoefficient($coefficient)
	{
		Validators::isNumeric($coefficient);
		$this->coefficient = $coefficient;
	}

	/**
	 * @return boolean
	 */
	public function isPayVAT()
	{
		return $this->payVAT;
	}

	/**
	 * @param boolean $payVAT
	 */
	public function setPayVAT($payVAT)
	{
		Validators::isBoolean($payVAT);
		$this->payVAT = $payVAT;
	}

	/**
	 * @return
	 */
	public function getRateVAT()
	{
		return $this->rateVAT;
	}

	/**
	 * @param mixed $rateVAT
	 */
	public function setRateVAT($rateVAT)
	{
		$rates = [
			"none" => "bez DPH",
			"high" => "Základní sazba",
			"low" => "Snížena sazba",
			"third" => "3. sazba (pouze SK verze)"
		];

		Validators::assertKeyInList($rateVAT, $rates);
		$this->rateVAT = $rateVAT;
	}

	/**
	 * @return float
	 */
	public function getPercentVAT()
	{
		return $this->percentVAT;
	}

	/**
	 * @param float $percentVAT
	 */
	public function setPercentVAT($percentVAT)
	{
		Validators::isNumeric($percentVAT);
		$this->percentVAT = $percentVAT;
	}

	/**
	 * @return float
	 */
	public function getDiscountPercentage()
	{
		return $this->discountPercentage;
	}

	/**
	 * @param float $discountPercentage
	 */
	public function setDiscountPercentage($discountPercentage)
	{
		Validators::isNumeric($discountPercentage);
		if ($discountPercentage < -999 || $discountPercentage > 999)
			throw new \InvalidArgumentException($discountPercentage . " must be betweeen -999 and 999");
		$this->discountPercentage = $discountPercentage;
	}

	/**
	 * @return array
	 */
	public function getHomeCurrency()
	{
		return $this->homeCurrency;
	}

	public function getUnitPrice()
	{
		return $this->homeCurrency["unitPrice"];
	}

	/**
	 * @param float $price
	 */
	public function setUnitPrice($price)
	{
		Validators::isNumeric($price);
		$this->homeCurrency["unitPrice"] = $price;
	}

	public function getPrice()
	{
		return $this->homeCurrency["price"];
	}

	/**
	 * @param float $price
	 */
	public function setPrice($price)
	{
		//without tax (vat)
		Validators::isNumeric($price);
		$this->homeCurrency["price"] = $price;
	}

	public function getPriceVat()
	{
		return $this->homeCurrency["priceVAT"];
	}

	/**
	 * @param float $price
	 */
	public function setPriceVat($price)
	{
		//only vat itself
		Validators::isNumeric($price);
		$this->homeCurrency["priceVAT"] = $price;
	}


	public function getPriceSum()
	{
		return $this->homeCurrency["priceSum"];
	}


	public function setPriceSum($price)
	{
		//price with vat
		Validators::isNumeric($price);
		$this->homeCurrency["priceSum"] = $price;
	}


	/**
	 * @return array
	 */
	public function getForeignCurrency()
	{
		return $this->foreignCurrency;
	}

	/**
	 * @param array $foreignCurrency
	 */
	public function setForeignCurrency(array $foreignCurrency)
	{
		$this->foreignCurrency = $foreignCurrency;
	}

	/**
	 * @return string
	 */
	public function getNote()
	{
		return $this->note;
	}

	/**
	 * @param string $note
	 */
	public function setNote($note)
	{
		if (Validators::isMaxLength($note, 90) === false)
			$note = mb_substr($note, 0, 90);
		$this->note = $note;
	}

	/**
	 * @return null|string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode($code)
	{
		Validators::assertMaxLength($code, 64);
		$this->code = $code;
	}

	/**
	 * @return int
	 */
	public function getGuarantee()
	{
		return $this->guarantee;
	}

	/**
	 * @param int $guarantee
	 */
	public function setGuarantee($guarantee)
	{
		Validators::assertNumeric($guarantee);
		$this->guarantee = $guarantee;
	}

	/**
	 * @return string
	 */
	public function getGuaranteeType()
	{
		return $this->guaranteeType;
	}

	/**
	 * @param string $guaranteeType
	 */
	public function setGuaranteeType($guaranteeType)
	{
		$types = [
			"none" => "bez záruky",
			"hour" => "v hodinách",
			"day" => "ve dnech",
			"month" => "v měsících",
			"year" => "v letech",
			"life" => "doživotní záruka",
		];

		Validators::assertKeyInList($guaranteeType, $types);
		$this->guaranteeType = $guaranteeType;
	}

	/**
	 * @return mixed
	 */
	public function getStockItem()
	{
		return $this->stockItem;
	}

	/**
	 * @param mixed $stockItem
	 */
	public function setStockItem($stockItem)
	{
		$this->stockItem = $stockItem;
	}


}