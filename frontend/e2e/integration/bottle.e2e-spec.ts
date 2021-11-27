describe('Create Bottle Page Tests', () => {
  beforeEach(() => {
    cy.visit('/bottle/create');
  });

  it('It should contains title', () => {
    cy.contains('Send a Bottle');
  });

  it('It should contains header', () => {
    cy.get('.header').should('exist');
  });

  it('It should contains footer', () => {
    cy.get('.footer').should('exist');
  });

  it('It should contains create-bottle component', () => {
    cy.get('.create-bottle').should('exist');
  });

  it('should contains a valid creation bottle form', () => {
    cy.get('#message').type('This is a test message!');
    cy.get('button[type=submit]').click();
    cy.get('.success').should('exist');
    cy.get('.success').should('be.visible');
  });
});
