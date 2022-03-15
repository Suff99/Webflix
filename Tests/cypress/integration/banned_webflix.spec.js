function login() {
    cy.fixture("test_data.json").then((account) => {
        cy.visit("https://craig.software/webflix/login.php");
        cy.get("input[name=email]")
            .should("be.visible")
            .type(account.banned_email);

        cy.get("input[name=password]")
            .should("be.visible")
            .type(account.banned_password);

        cy.get("button[name=login]").should("be.visible").click();
    });
};

describe("Banned Users", () => {
    it("Validate Banned users cannot access web application", () => {
        login();
        cy.contains("This account is banned and is not permitted to use this service.").should('exist').should('be.visible')
    });
});
