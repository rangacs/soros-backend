#!/bin/sh

cat template_logproperties.xml | sed -i 's/sabia_dbname/zlib.output_compression = On/g' > auto_offset_logproperties.xml

