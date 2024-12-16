DIRECTORY="../../assets"
if [ ! -d "$DIRECTORY" ]; then
  mkdir -p "$DIRECTORY"
fi

DIRECTORY="../../protected/runtime"
if [ ! -d "$DIRECTORY" ]; then
  mkdir -p "$DIRECTORY"
fi

DIRECTORY="../../tmp/uploads"
if [ ! -d "$DIRECTORY" ]; then
  mkdir -p "$DIRECTORY"
fi

DIRECTORY="../../tmp/runlog"
if [ ! -d "$DIRECTORY" ]; then
  mkdir -p "$DIRECTORY"
fi

chmod 777 ../../assets
chmod 777 ../../protected/runtime 
chmod 777 ../../tmp
chmod 777 ../../tmp/uploads
chmod 777 ../../tmp/runlog
