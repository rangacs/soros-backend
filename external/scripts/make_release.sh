#!/bin/sh
password=OvalOval@11

echo "cleaning directories..."
./support_shell_scripts/clean_dirs.sh
cd ../../

packagedir="/tmp/helios-package-temp-dir"
DIRECTORY="$packagedir"
if [ ! -d "$DIRECTORY" ]; then
  mkdir -p "$DIRECTORY"
else
  rm -rf "$DIRECTORY"
  mkdir -p "$DIRECTORY"
fi


DIRECTORY="external/release-package"
if [ ! -d "$DIRECTORY" ]; then
  mkdir -p "$DIRECTORY"
fi

echo "finding head..."
head_name=$(git rev-parse --short HEAD)
full_commit_date=$(git show -s --format=%ci HEAD^{commit})
short_date=${full_commit_date:0:16}
short_date=${short_date// /_}
short_date=${short_date//:/_}
echo "$head_name - $full_commit_date" > helios1_version
packagename="helios1-release-$head_name-$short_date"
echo "Preparing release package: $packagename.zip"

fullpackagename="$packagedir/$packagename"
FILE="$fullpackagename.zip"
if [ -f $FILE ]; then
   echo "removing old package $FILE"
   rm -f "$FILE"
#else
   #echo "File $FILE does not exist."
fi

echo "compressing..."
tar --exclude='*.tgz' --exclude='./.git' --exclude='./tmp'  --exclude='./runtime' --exclude='./assets' --exclude='./external/release-package' --exclude='./external/scripts/tmp' --exclude='./nbproject' --exclude='./react' --exclude='./node_modules' -czf "$packagedir/helios1".tgz .
#tar --exclude='*.tgz' --exclude='./.git' --exclude='./tmp'  --exclude='./runtime' --exclude='./assets' --exclude='./external/release-package' --exclude='./external/scripts/tmp' --exclude='./nbproject' --exclude='./react' --exclude='./node_modules' -czf "$fullpackagename".tgz external/scripts


echo "encrypting with password $password"
#/usr/local/sabia-ck/bin/7za a "$fullpackagename".7z "$fullpackagename".tar -pOvalOval@11
zip -rj -P "$password" "$fullpackagename.zip" "$packagedir/helios1".tgz

FILE="external/release-package/$packagename.zip"
if [ -f $FILE ]; then
   echo "removing old package $FILE"
   rm -f "$FILE"
else
   mv "$fullpackagename".zip external/release-package/
fi

rm "packagedir/"helios1.tgz -f

wd=$(pwd)
echo "release package at location $wd/external/release-package/"

