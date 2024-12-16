#!/bin/sh
echo "installing helios simulation cronjobs..."

helios_sim_cmd="/var/www/html/helios1/external/simulation/helios_sim.sh"
helios_sim_cron="*/1 * * * * /var/www/html/helios1/external/simulation/helios_sim.sh >> /var/www/html/helios1/external/simulation/helios_sim-`date +\%m-\%d-\%Y`-cron.log 2>&1"

#remove old
../scripts/support_shell_scripts/cron_mgmt.sh remove "$helios_sim_cmd"

#add new
../scripts/support_shell_scripts/cron_mgmt.sh add "$helios_sim_cron"
