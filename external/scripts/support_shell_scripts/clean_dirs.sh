#!/bin/sh
rm -rf ../../protected/runtime/*
rm -rf ../../assets/*
rm -rf ../../tmp/uploads/*
rm -rf ../release-package/*
#delete all temperory files
find ../../ -name '*~' -delete

DIRECTORY="../release-package"
if [ -d "$DIRECTORY" ]; then
	rm -rf "$DIRECTORY"
fi

