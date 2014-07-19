all: clean apigen

apigen:
	vendor/bin/apigen.php --source src/ --destination api/ --exclude="*/Tests/*"

clean:
	rm -rf api/*