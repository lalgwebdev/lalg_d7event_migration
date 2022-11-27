<?php

namespace Drupal\lalg_d7_event_migration\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePreRowSaveEvent;
use Drupal\recurring_events\Plugin\migrate\process\RRuleHelper;

class StoreIntervalSubscriber implements EventSubscriberInterface {
  
  public function onPreRowSave(MigratePreRowSaveEvent $event) {
    if($event->getMigration()->id() != 'events') {
      return;
    }
    $eventdate = $event->getRow()->getSourceProperty('field_eventdate');
    $rrule_in = $enentdate[0]['rrule'] ?? NULL;
    $rrule = isset($rrule_in) ? RRuleHelper::parseRule($rrule_in) : [ 'interval' -> 1 ];
    
    // Currently not dealing with monthly interval
    $weekly = $event->getRow()->getDestinationProperty('weekly_recurring_date');
    $weekly['weekly_interval'] = $rrule['interval'];
    $event->getRow()->setDestinationProperty('weekly_recurring_date', $weekly);
  }
  
  public static function getSubscribedEvents() {
    return array(
      MigrateEvents::PRE_ROW_SAVE => 'onPreRowSave'
    );
  }
}
