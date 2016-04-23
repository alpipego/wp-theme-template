#!/bin/bash

# Save this somewhere in your path as "git-archive-file"
# and do 'chmod u+x git-archive-file'

# If run at tag "v10.0", archvie file will be named "repo-v10.0.zip"
# If run after one commit after a tag, file will be named "repo.v10.0-1-gdc03bc1.branchname.zip"

# adapted from
# http://blog.thehippo.de/2012/03/tools-and-software/how-to-create-a-custom-git-command-extension/

# if on a tag, append that to the filename
VERSION=$(git describe --tags --always)

# extract current branch name
BRANCH_NAME=$(git name-rev HEAD 2> /dev/null | awk "{ print \$2 }")

# if not on master append branch name into the filename
if [ "$BRANCH_NAME" != "master" ]; then
    BRANCH=.$BRANCH_NAME
else
    BRANCH=''
fi

# get name of the most top folder of git repo directory,
# combine with revision tail
OUTPUT=$(basename $(git rev-parse --show-toplevel)).$VERSION$BRANCH.zip

# building archive
git archive --format zip --output $OUTPUT $BRANCH_NAME

echo $OUTPUT
