<?php
protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
{
  $schedule->command('leads:auto-assign')->everyFiveMinutes();
}