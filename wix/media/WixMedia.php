<?php

abstract class WixMedia {
	protected $id;
	private $client;
	private $metadata;
	private $metadata_ts;

	public function __construct($media_id = '', WixClient $client) {
		$this->id = $media_id;
		$this->client = $client;
	}

	public function getId() {
		return $this->id;
	}

	public function getMetadataId() {
		if (!$this->id) throw new Exception("media_id must be set", 20);
		
		list($user_id, $partition, $file_id, $file_name) = explode('/', $this->id);
		return $file_id;
	}

	public function getMetadataTs() {
		return $this->metadata_ts;
	}

	abstract public function getUrl();

	public function getMetadata($refresh = false) {
		if (!$this->metadata || $refresh) {
			$this->metadata = $this->client->getMetadata($this->getMetadataId());
			$mct = explode(" ",microtime());
			$this->metadata_ts = date("Y-m-d\TH:i:s",$mct[1]).substr((string)$mct[0],1,4).'Z';
		}
		return $this->metadata;
	}
}