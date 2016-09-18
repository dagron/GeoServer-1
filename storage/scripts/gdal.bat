cd c:/ms4w
call setenv.bat
gdal_translate -of vrt -expand rgba %1 %2
