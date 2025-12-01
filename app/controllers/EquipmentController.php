<?php

require_once __DIR__ . '/Equipment.php';

/**
 * Legacy alias for routes that still point to /equipmentcontroller/*
 * Delegates all behavior to the consolidated Equipment controller.
 */
class EquipmentController extends Equipment
{
}
