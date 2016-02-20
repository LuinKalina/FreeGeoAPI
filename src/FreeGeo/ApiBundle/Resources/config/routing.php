<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('api_auth', new Route('/api/authenticate/session', array(
    '_controller' => 'FreeGeoApiBundle:ApiAuth:session'
)));

$collection->add('api_find_near_airport', new Route('/api/location/near/airport', array(
    '_controller' => 'FreeGeoApiBundle:FindNear:airports'
)));

$collection->add('api_find_near_city', new Route('/api/location/near/city', array(
    '_controller' => 'FreeGeoApiBundle:FindNear:cities'
)));

$collection->add('api_find_near_port', new Route('/api/location/near/port', array(
    '_controller' => 'FreeGeoApiBundle:FindNear:ports'
)));

$collection->add('api_time_zone', new Route('/api/detect/timezone', array(
    '_controller' => 'FreeGeoApiBundle:Detection:timezone'
)));

$collection->add('api_calculate_distance', new Route('/api/calculate/distance', array(
    '_controller' => 'FreeGeoApiBundle:Api:calculateDistance'
)));

$collection->add('api_detect_country', new Route('/api/detect/country', array(
    '_controller' => 'FreeGeoApiBundle:Detection:country'
)));

$collection->add('api_reset_keys', new Route('/api/credentials/reset', array(
    '_controller' => 'FreeGeoApiBundle:Api:resetKeys'
)));

// This block of routes is not on prod. These requests will be too heavy since they are
//Multipolygons

// $collection->add('api_find_near_lake', new Route('/api/location/near/lake', array(
// 	'_controller' => 'FreeGeoApiBundle:Api:findNearLake'
// )));

// $collection->add('api_find_near_road', new Route('/api/location/near/road', array(
// 	'_controller' => 'FreeGeoApiBundle:Api:findNearRoad'
// )));

// $collection->add('api_find_near_railroad', new Route('/api/location/near/ralroad', array(
// 	'_controller' => 'FreeGeoApiBundle:Api:findnearRailraod'
// )));

//Utility Routes (DO NOT PUBLISH TO PROD)

$collection->add('generate_keys', new Route('/utility/generate', array(
    '_controller' => 'FreeGeoApiBundle:Utility:generate'
)));

$collection->add('test_find_near', new Route('/utility/location/find-near', array(
    '_controller' => 'FreeGeoApiBundle:Utility:findNear'
)));

return $collection;
