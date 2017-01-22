<?php
	namespace PHPSTORM_META {
	/** @noinspection PhpUnusedLocalVariableInspection */
	/** @noinspection PhpIllegalArrayKeyTypeInspection */
	$STATIC_METHOD_TYPES = [

		\D('') => [
			'Adv' instanceof Think\Model\AdvModel,
			'Mongo' instanceof Think\Model\MongoModel,
			'View' instanceof Think\Model\ViewModel,
			'DeviceInstall' instanceof Home\Model\DeviceInstallModel,
			'Relation' instanceof Think\Model\RelationModel,
			'DeviceInfo' instanceof Home\Model\DeviceInfoModel,
			'DeviceProblem' instanceof Home\Model\DeviceProblemModel,
			'Customer' instanceof Home\Model\CustomerModel,
			'Merge' instanceof Think\Model\MergeModel,
		],
	];
}