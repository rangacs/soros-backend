#!/bin/sh

usage () {
    cat <<USAGE_END
Usage:
    $0 add "job-details"
    $0 list
    $0 remove "job-details-lineno"
USAGE_END
}

if [ -z "$1" ]; then
    usage >&2
    exit 1
fi

case "$1" in
    add)
        if [ -z "$2" ]; then
            usage >&2
            exit 1
        fi
	echo adding job "$2"
	crontab -l | { cat; echo "$2"; } | crontab -

        ;;
    list)
        crontab -l | cat -n
        ;;
    remove)
        if [ -z "$2" ]; then
            usage >&2
            exit 1
        fi
	echo removing job "$2"
        crontab -l | grep -v "$2" | crontab -
        ;;
    *)
        usage >&2
        exit 1
        ;;
esac
