yarn write-translations --locale ja
mkdir -p i18n/ja/docusaurus-plugin-content-docs/current
cp -r -n docs/** i18n/ja/docusaurus-plugin-content-docs/current
mkdir -p i18n/ja/docusaurus-plugin-content-blog
cp -r -n blog/** i18n/ja/docusaurus-plugin-content-blog

yarn write-translations --locale zh
mkdir -p i18n/zh/docusaurus-plugin-content-docs/current
cp -r -n docs/** i18n/zh/docusaurus-plugin-content-docs/current
mkdir -p i18n/zh/docusaurus-plugin-content-blog
cp -r -n blog/** i18n/zh/docusaurus-plugin-content-blog
