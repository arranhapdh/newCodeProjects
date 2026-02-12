<?php

logout_user();
session_start();
flash('You have been logged out.', 'success');
redirect('login');
