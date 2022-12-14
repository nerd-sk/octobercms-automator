const { By, Builder, Key, until } = require('selenium-webdriver');
const { suite } = require('selenium-webdriver/testing');
const fs = require('fs');
const assert = require("assert");
const password = process.env.ADMINPASSWORD;
const adminurl = process.env.ADMINURL;

describe('Create admin user', function() {
    let driver;

    before(async function() {
        driver = await new Builder().forBrowser('chrome').build();
    });

    after(async () => await driver.quit());

    it('Login user', async function() {

        await driver.get(adminurl);
        await driver.manage().setTimeouts({ implicit: 5000 });
        await driver.wait(until.elementLocated(By.id('adminFirstName')), 20000, 'Timed out after 1 seconds', 1000);

        await driver.findElement(By.id("adminFirstName")).sendKeys("Your-name");
        await driver.findElement(By.id("adminLastName")).sendKeys("Your-surname");
        await driver.findElement(By.id("adminEmail")).sendKeys("your-email-address");
        await driver.findElement(By.id("adminLogin")).sendKeys("your-username");
        await driver.findElement(By.id("adminPassword")).sendKeys(password);
        await driver.findElement(By.id("adminConfirmPassword")).sendKeys(password,Key.RETURN);

        let title = await driver.getTitle();
        console.log(title);
        assert.equal("Dashboard | October CMS", title);

    }).timeout(30000);
});