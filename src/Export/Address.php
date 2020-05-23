<?php
/**
 * Author: Ivo Toman
 */

namespace Pohoda\Export;

use Pohoda\Export;
use SimpleXMLElement;
use Pohoda\Object\Identity;

class Address implements IExport
{
	const NS = "http://www.stormware.cz/schema/version_2/addressbook.xsd";
	const ADDRESS = "address";
	const SHIPTO = "shipToAddress";

	/** @var Identity $identity */
	private $identity;


	/**
	 * Address constructor.
	 * @param Identity $identity
	 */
	public function __construct(Identity $identity)
	{
		$this->identity = $identity;
	}


	public function export(SimpleXMLElement $xml)
	{
		$xmlAd = $xml->addChild('adb:addressbook', null, self::NS);
		$xmlAd->addAttribute('version', '2.0');
		$this->exportFilter($xmlAd->addChild('adb:actionType'), null, self::NS);
		$this->exportHeader($xmlAd->addChild('adb:addressbookHeader', null, self::NS));

		return $xmlAd;
	}

	/**
	 * Filtr pro import osoby do adresare pohody, pokud uz tam je, tak nic neimportuje
	 * @param SimpleXMLElement $xml
	 */
	public function exportFilter(SimpleXMLElement $xml)
	{
		$filter = $xml->addChild('adb:add', null, self::NS)
			->addChild('ftr:filter', null, Export::NS_FTR);
		if($this->getIdentity()->hasId()) {
			$ext = $filter->addChild('ftr:extId', null, Export::NS_FTR);
			$ext->addChild('typ:ids', $this->getIdentity()->getId(), Export::NS_TYPE);
			$ext->addChild('tpy:exSystemName', 'Unio', Export::NS_TYPE);
		} else {
			$adr = $this->getIdentity()->getAddress();
			$filter->addChild('ftr:company', $adr->getCompany(), Export::NS_FTR);
			$filter->addChild('ftr:name', $adr->getName(), Export::NS_FTR);
			$filter->addChild('ftr:street', $adr->getStreet(), Export::NS_FTR);
			$filter->addChild('ftr:city', $adr->getCity(), Export::NS_FTR);
		}
	}


	public function exportHeader(SimpleXMLElement $header)
	{
		$xmlIdenti = $header->addChild('adb:identity', null, self::NS);
		$this->exportAddress($xmlIdenti);
		$info = $this->getIdentity()->getAddress();
		$header->addChild('adb:mobil', $info->getPhone(), self::NS);
		$header->addChild('adb:email', $info->getEmail(), self::NS);

	}


	public function exportAddress(SimpleXMLElement $xml)
	{
		$identity = $this->getIdentity();

		if ($identity->hasId()) {
			$ext = $xml->addChild('typ:extId', null, Export::NS_TYPE);
			$ext->addChild('typ:ids', $identity->getId(), Export::NS_TYPE);
			$ext->addChild('tpy:exSystemName', 'Unio', Export::NS_TYPE);
		}

		$this->exportAddressXml($xml, $identity->getAddress());

		if ($identity->hasShippingAddress())
			$this->exportAddressXml($xml, $identity->getShippingAddress(), self::SHIPTO);
	}


	public function exportAddressXml(SimpleXMLElement $xml, \Pohoda\Object\Address $address, $type = self::ADDRESS)
	{

		$xmlAd = $xml->addChild('typ:' . $type, null, Export::NS_TYPE);

		if ($address->getCompany()) {
			$xmlAd->addChild('typ:company', $address->getCompany());
		}

		if ($address->getName()) {
			$xmlAd->addChild('typ:name', $address->getName());
		}

		if ($address->getDivision()) {
			$xmlAd->addChild('typ:division', $address->getDivision());
		}

		if ($address->getCity()) {
			$xmlAd->addChild('typ:city', $address->getCity());
		}

		if ($address->getStreet()) {
			$xmlAd->addChild('typ:street', $address->getStreet());
		}

		if ($address->getNumber()) {
			$xmlAd->addChild('typ:number', $address->getNumber());
		}

		if ($address->getZip()) {
			$xmlAd->addChild('typ:zip', $address->getZip());
		}

		if ($address->getCountry()) {
			$xmlAd->addChild('typ:country')->addChild('typ:ids', $address->getCountry());
		}

		if ($address->getIco()) {
			$xmlAd->addChild('typ:ico', $address->getIco());
		}

		if ($address->getDic()) {
			$xmlAd->addChild('typ:dic', $address->getDic());
		}

		if ($address->getIcDph()) {
			$xmlAd->addChild('typ:icDph', $address->getIcDph());
		}

		if ($address->getPhone()) {
			$xmlAd->addChild('typ:mobilPhone', $address->getPhone());
		}

		if ($address->getEmail()) {
			$xmlAd->addChild('typ:email', $address->getEmail());
		}

	}


	/**
	 * @return Identity
	 */
	public function getIdentity()
	{
		return $this->identity;
	}

	/**
	 * @return null|mixed
	 */
	public function getId()
	{
		return $this->identity->getId();
	}
}
