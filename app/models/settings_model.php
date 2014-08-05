<?php
class SettingsModel extends Model {
	
	public function getSettings(){
		$connection = DB::connect();
		$stmt = $connection->prepare("SELECT * FROM `site_settings`");
		$stmt->execute();
		$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $settings;
	}
	
	public function saveSettings($data){
		$connection = DB::connect();
		foreach ($data as $item => $key){
			$stmt = $connection->prepare("UPDATE `site_settings` SET `value` = :value WHERE `key`=:key");
			$stmt->bindParam(':value', $key);
			$stmt->bindParam(':key', $item);
			$stmt->execute();
		}
	}
	
	public function getSetting($key){
		$connection = DB::connect();
		$stmt = $connection->prepare("SELECT `key`, `value` FROM `site_settings` WHERE `key` = :key");
		$stmt->bindParam(':key', $key);
		$stmt->execute();
		$setting = $stmt->fetch(PDO::FETCH_ASSOC);
		return $setting;
	}
	
	
}