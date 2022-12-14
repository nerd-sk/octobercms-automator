const { By, Builder, Key, until } = require('selenium-webdriver');
const { suite } = require('selenium-webdriver/testing');
const fs = require('fs');
const assert = require("assert");
const name = process.env.NAME;



describe('Create project licence key', function() {
    let driver;

    before(async function() {
        driver = await new Builder().forBrowser('chrome').build();
    });

    after(async () => await driver.quit());

    it('Login user', async function() {

        await driver.get('https://octobercms.com/account/project/create');
        await driver.manage().setTimeouts({ implicit: 5000 });

        let username = await driver.findElement(By.id("signInEmail")).sendKeys("your-email");
        let password = await driver.findElement(By.id("signInPassword")).sendKeys("your-password",Key.RETURN);

        await driver.wait(until.elementLocated(By.id('project-name')), 10000, 'Timed out after 10 seconds', 1000);
        await driver.findElement(By.id("project-name")).sendKeys(name,Key.RETURN);

        await driver.wait(until.elementLocated(By.id('show-project-id')), 10000, 'Timed out after 10 seconds', 1000);

        await driver.findElement(By.id("show-project-id")).click();
        await driver.manage().setTimeouts({ implicit: 5000 });

        let title = await driver.getTitle();
        console.log(title);
        assert.equal("Project - October CMS", title);

        
        let licenceKey = await driver.findElement(By.css(".modal-panel-paddings .alert-success")).getText();
        console.log(licenceKey);

        fs.writeFile('product-key.txt', licenceKey, function (err) {
          if (err) return console.log(err);
          console.log(licenceKey+' > product-key.txt');
        });

    }).timeout(30000);
});