<?php
symlink('/home/dh_2gujjy/raleighgroesbeck-app/storage/app/public', '/home/dh_2gujjy/wtiestimating.raleighgroesbeck.com/storage');
// DON'T FORGET TO DELETE EXISTING STORAGE FOLDER IN PRODUCTION!!!

// 2 METHODS OF CREATING SYMLINKS
// Route::get('/link', function () {
//     Artisan::call('storage:link');
// });

// Route::get('/link', function () {
//    $target = '/home/dh_2gujjy/raleighgroesbeck-app/storage/app/public';
//    $shortcut = '/home/dh_2gujjy/raleighgroesbeck.com/storage';
//    symlink($target, $shortcut);
// });
