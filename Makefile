TAG := $(shell git describe --tags --abbrev=0)
VERSION := $(shell echo $(TAG) | sed 's/v//' )
# VERSION = $(TAG)

HASH := $(shell git log --pretty="%h" -n1 HEAD)
date := $(shell git log -n1 --pretty=%ci HEAD)

PKGDIR = demoinfo-$(VERSION)

#return [
#    'tag' => $tag,
#        'date' => $date,
#	    'hash' => $hash,
#	        'string' => sprintf('%s-%s (%s)', $tag, $hash, $date->format('Y-m-d H:i')),
#		];

all:
	@echo "No actions defined"
	@echo "VERSION = $(VERSION)"
	@echo "TAG     = $(TAG)"
	@echo "HASH    = $(HASH)"
	@echo "date    = $(date)"

clean:
	@rm -rf *~ *.bak

pkg:
	mkdir -p $(PKGDIR)/debian
	bash ./tools/git2debchangelog.sh > $(PKGDIR)/debian/changelog
	cp crontab/crontab.demoinfo $(PKGDIR)/debian/demoinfo.cron
	cp debian/rules $(PKGDIR)/debian/rules
	cd $(PKGDIR)
	debuild -i -us -uc -b


