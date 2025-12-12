<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('users:delete-scheduled')->hourly();
