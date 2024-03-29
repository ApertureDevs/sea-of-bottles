describe('Landing Page Tests', () => {
  beforeEach(() => {
    cy.visit('/landing');
  });

  it('should contains title', () => {
    cy.get('.jumbotron__main-title').contains('Sea of Bottles');
  });

  it('should contains header', () => {
    cy.get('.header').should('exist');
  });

  it('should contains footer', () => {
    cy.get('.footer').should('exist');
  });

  it('should contains landing', () => {
    cy.get('.landing').should('exist');
  });

  it('should contains sea counters', () => {
    cy.get('.counter-section').should('exist');
  });
});
