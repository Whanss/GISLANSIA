# Fix Lansia Show Route Parameter Error

## Plan Summary

The Laravel resource route `lansia.show` expects parameter `lansium` instead of standard `lansia`, causing URL generation errors when no parameter provided.

## Steps

- [ ] Edit routes/web.php: Add `['parameters' => ['lansia' => 'lansia']]` to Route::resource
- [ ] Execute `php artisan route:clear`
- [ ] Verify routes with `php artisan route:list | findstr lansia` (should show `{lansia}`)
- [ ] Test navigation to lansia show page
