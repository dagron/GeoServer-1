cd c:/ms4w
call setenv.bat
gdal_translate -of vrt %1 %2
