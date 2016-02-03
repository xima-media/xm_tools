#!/bin/bash

# generate api doc in rst format
sphpdox process --output "docs/source/_static/sphinxcontrib-phpdomain" "Xima\XmTools" Classes

# generate api doc in html format
cd build; phpdoc

# put it all together
cd ../docs/; make html; cd ..