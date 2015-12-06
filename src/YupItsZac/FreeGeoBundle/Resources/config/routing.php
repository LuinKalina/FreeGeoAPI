<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

$collection = new RouteCollection();

//web Routes

$collection->add('free_geo_homepage', new Route('/', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:index',
)));

$collection->add('free_geo_status', new Route('/status', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'status',
    'dir' => 'Web'
)));

$collection->add('free_geo_app_register', new Route('/apps/register', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:appRegister'
)));

$collection->add('free_geo_authenticate', new Route('/docs/authenticate', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'authenticate',
    'dir' => 'Docs'
)));

$collection->add('free_geo_authenticate_session', new Route('/docs/authenticate/session', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'authenticate',
    'dir' => 'Docs'
)));

$collection->add('free_geo_reset', new Route('/docs/reset-api-keys', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'resetkeys',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near', new Route('/docs/requests/find-near', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnear',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near_airports', new Route('/docs/requests/find-near/airports', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnearairport',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near_cities', new Route('/docs/requests/find-near/cities', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnearcities',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near_ports', new Route('/docs/requests/find-near/ports', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnearports',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near_lakes', new Route('/docs/requests/find-near/lakes', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnearlakes',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near_roads', new Route('/docs/requests/find-near/roads', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnearroads',
    'dir' => 'Docs'
)));

$collection->add('free_geo_find_near_railroads', new Route('/docs/requests/find-near/railroads', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'findnearrailroads',
    'dir' => 'Docs'
)));

$collection->add('free_geo_detect_time_zone', new Route('/docs/requests/detect/timezone', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'detecttimezone',
    'dir' => 'Docs'
)));

$collection->add('free_geo_calculate_distance', new Route('/docs/requests/calculate/distance', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'calculatedistance',
    'dir' => 'Docs'
)));

$collection->add('free_geo_todo_high', new Route('/to-do/priority-high', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'high',
    'dir' => 'Todo'
)));

$collection->add('free_geo_todo_medium', new Route('/to-do/priority-medium', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'medium',
    'dir' => 'Todo'
)));

$collection->add('free_geo_todo_low', new Route('/to-do/priority-low', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'low',
    'dir' => 'Todo'
)));

$collection->add('free_geo_contribute', new Route('/contribute', array(
    '_controller' => 'YupItsZacFreeGeoBundle:Web:staticRender',
    'action' => 'index',
    'dir' => 'Contribute'
)));


//API Routes

$collection->add('api_auth', new Route('/api/authenticate/session', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:auth'
)));

$collection->add('api_find_near_airport', new Route('/api/location/near/airport', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:findNearAirport'
)));

$collection->add('api_find_near_city', new Route('/api/location/near/city', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:findNearCity'
)));

$collection->add('api_find_near_port', new Route('/api/location/near/port', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:findNearPort'
)));

$collection->add('api_time_zone', new Route('/api/detect/timezone', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:timeZone'
)));

$collection->add('api_calculate_distance', new Route('/api/calculate/distance', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:calculateDistance'
)));

$collection->add('api_detect_country', new Route('/api/detect/country', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:detectCountry'
)));

$collection->add('api_reset_keys', new Route('/api/credentials/reset', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Api:resetKeys'
)));

// This block of routes is not on prod. These requests will be too heavy since they are
//Multipolygons

// $collection->add('api_find_near_lake', new Route('/api/location/near/lake', array(
// 	'_controller' => 'YupItsZacFreeGeoBundle:Api:findNearLake'
// )));

// $collection->add('api_find_near_road', new Route('/api/location/near/road', array(
// 	'_controller' => 'YupItsZacFreeGeoBundle:Api:findNearRoad'
// )));

// $collection->add('api_find_near_railroad', new Route('/api/location/near/ralroad', array(
// 	'_controller' => 'YupItsZacFreeGeoBundle:Api:findnearRailraod'
// )));

//Utility Routes (DO NOT PUBLISH TO PROD)

$collection->add('generate_keys', new Route('/utility/generate', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Utility:generate'
)));

$collection->add('test_find_near', new Route('/utility/location/find-near', array(
	'_controller' => 'YupItsZacFreeGeoBundle:Utility:findNear'
)));



return $collection;
